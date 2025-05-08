<?php

namespace App\Licitation;

use App\AiChatModel\AiChatModelInterface;
use App\Core\LicitationPromptType;
use App\Entity\LicitationAnswer\LicitationAnswer;
use App\Entity\LicitationPlayer\LicitationPlayer;
use App\Entity\LicitationItem\LicitationItem;
use App\Entity\LicitationMove\LicitationMove;
use App\Entity\LicitationGame\LicitationGame;
use App\Prompt\Licitation\RulesPrompt;
use App\Prompt\Licitation\WrongAnswerPrompt;
use App\Repository\LicitationAnswerRepository;
use App\Repository\LicitationGameRepository;
use App\Repository\LicitationItemRepository;
use App\Repository\LicitationMoveRepository;
use App\Repository\LicitationPlayerRepository;

class LicitationGameService
{
    public int $gameId;
    /**
     * @var LicitationItem[] $licitationItems
     */
    public array $licitationItems;
    /**
     * @var Player[] $players
     */
    public array $players;
    /**
     * @var Item[] $items
     */
    public array $items;

    /**
     * @var AiChatModelInterface[] $playerss
     * @var int[] $itemss
     */
    public function __construct(
        private array $playerss,
        private readonly array             $itemss,
        private LicitationGameRepository   $licitationGameRepository,
        private LicitationAnswerRepository $licitationAnswerRepository,
        private LicitationItemRepository   $licitationItemRepository,
        private LicitationMoveRepository   $licitationMoveRepository,
        private LicitationPlayerRepository $licitationPlayerRepository,
        private LicitationPromptType       $promptType,
        public int                         $numberOfRepeats,
        public int                         $numberOfTokens,
    ){
        $this->players = array_map(
            fn($player) => new Player($player),
            $playerss
        );
        $this->items = array_map(
            fn($item) => new Item($item),
            $itemss
        );
    }

    public function startGame(): void
    {
        $game = new LicitationGame(
            $this->promptType,
            $this->numberOfRepeats,
            $this->numberOfTokens,
        );

        $this->licitationGameRepository->add($game);
        $this->gameId = $game->getId();

        foreach ($this->players as $player)
        {
            $this->licitationPlayerRepository->add(
                new LicitationPlayer(
                    $player->getModel()->getIdentifier(),
                    $this->gameId,
                )
            );
        }

        $itemCounter = 1;
        foreach ($this->items as $item)
        {
            $this->licitationItemRepository->add(
                $this->licitationItems[] = new LicitationItem(
                    $itemCounter,
                    $this->gameId,
                    $item->getValue()
                )
            );
            $itemCounter++;
        }

        $this->game();
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function game(): void
    {
        if($this->promptType == LicitationPromptType::Normal)
        {
            $players = $this->getPlayers();
            foreach ($players as $player)
            {
                $moveCount = 1;
                $answer = $player->getModel()->sendMessage(
                    RulesPrompt::getPrompt(
                        count($this->players),
                        $this->items,
                        $this->numberOfTokens
                    )
                );
                $wasMoveCorrect = $this->checkIfMoveWasCorrect($answer, $this->items);
                $this->licitationAnswerRepository->add(
                    new LicitationAnswer(
                        $this->gameId,
                        $player->getModel()->getIdentifier(),
                        $moveCount,
                        $answer,
                        $wasMoveCorrect
                    )
                );

                while(!$wasMoveCorrect)
                {
                    if($moveCount == $this->numberOfRepeats)
                    {
                        $this->licitationPlayerRepository->setEliminated($this->gameId, $player->getModel()->getIdentifier());
                        break;
                    }
                    $moveCount++;
                    $answer = $player->getModel()->sendMessage(
                        WrongAnswerPrompt::getPrompt()
                    );
                    $wasMoveCorrect = $this->checkIfMoveWasCorrect($answer, $this->items);
                    $this->licitationAnswerRepository->add(
                        new LicitationAnswer(
                            $this->gameId,
                            $player->getModel()->getIdentifier(),
                            $moveCount,
                            $answer,
                            $wasMoveCorrect
                        )
                    );
                }

                if($wasMoveCorrect)
                {
                    $answerMoves = $this->createAnswerMovesFromAnswer($answer);
                    $currentItemCounter = 0;
                    foreach ($answerMoves as $answerMove)
                    {
                        $this->licitationMoveRepository->add(
                            new LicitationMove(
                                $this->gameId,
                                $player->getModel()->getIdentifier(),
                                $this->licitationItems[$currentItemCounter]->getItemNumber(),
                                $answerMove->getValue(),
                            )
                        );
                        $currentItemCounter++;
                    }
                }
            }
        }
        $this->setWinnerOrWinners();
    }

    private function setWinnerOrWinners(): void
    {
        $players = $this->licitationPlayerRepository->getPlayers($this->gameId);
        $playerScores = [];
        foreach ($players as $player)
        {
            $playerScores[$player->getId()] = 0;
        }

        $itemCounter = 1;
        foreach ($this->items as $item)
        {
            $moves = $this->licitationMoveRepository->getMoves(
                $this->gameId,
                $itemCounter,
            );
            $itemCounter++;

            $maxValue = 0;
            foreach ($moves as $move)
            {
                if($move->getValue() > $maxValue)
                {
                    $maxValue = $move->getValue();
                }
            }
            foreach ($moves as $move)
            {
                if($move->getValue() == $maxValue)
                {
                    $playerScores[$move->getPlayerId()] += $item->getValue();
                }
            }
        }

        $maxScore = 0;
        foreach ($playerScores as $playerScore)
        {
            if($playerScore > $maxScore)
            {
                $maxScore = $playerScore;
            }
        }

        $winners = [];
        foreach ($playerScores as $playerId => $playerScore)
        {
            if($playerScore == $maxScore)
            {
                $winners[] = $playerId;
            }
        }

        foreach ($winners as $winner)
        {
            $this->licitationPlayerRepository->setWinner($this->gameId, $winner);
        }
    }

    /**
     * @return Item[]
     */
    private function createAnswerMovesFromAnswer(string $answer): array
    {
        $numbers = explode(',', $answer);
        $answerMoves = [];
        foreach ($numbers as $number)
        {
            $answerMoves[] = new Item((int) $number);
        }
        return $answerMoves;
    }

    /**
     * @param Item[] $items
     */
    private function checkIfMoveWasCorrect(string $answer, array $items): bool
    {
        $filteredAnswer = '';
        for ($i = 0; $i < strlen($answer); $i++) {
            if (preg_match('/[0-9,]/', $answer[$i])) {
                $filteredAnswer .= $answer[$i];
            } else {
                break;
            }
        }
        if (!preg_match('/^\d+(,\d+)*$/', $filteredAnswer)) {
            return false;
        }

        $numbers = explode(',', $filteredAnswer);

        if (count($numbers) !== count($items)) {
            return false;
        }

        $sum = 0;
        foreach ($numbers as $number) {
            if (!ctype_digit($number)) {
                return false;
            }
            $sum += (int) $number;
        }

        if ($sum > $this->numberOfTokens) {
            return false;
        }

        return true;
    }
}
