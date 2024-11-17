<?php

namespace App\Controller;

use App\Form\GameSetupType;
use App\Repository\TicTacToeGameRepository;
use App\Repository\TicTacToeMoveRepository;
use App\Service\AiChatModelLocator;
use App\TicTacToe\Command\TicTacToeGameCommand;
use App\TicTacToe\Player;
use App\TicTacToe\TicTacToeGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TicTacToeController extends AbstractController
{
    public function __construct(
        private TicTacToeGameRepository $ticTacToeGameRepository,
        private TicTacToeMoveRepository $ticTacToeMoveRepository,
        private MessageBusInterface $messageBus,
    ){
    }

    #[Route('/', name: 'setup_before_game', methods: ['GET', 'POST'])]
    public function setupBeforeGame(Request $request, AiChatModelLocator $modelLocator, SessionInterface $session): Response
    {
        $models = $modelLocator->getModels();

        $form = $this->createForm(GameSetupType::class, null, [
            'models' => $models,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $model1 = $data['model1'];
            $model2 = $data['model2'];
            $game = $data['game'];
            $typeOfProcess = $data['typeOfProcess'];
            $numberOfGames = $data['numberOfGames'];

            if($game === 'tictactoe' && $typeOfProcess === 'browser') {
                $session->set(
                    'players',
                    [
                        new Player($model1),
                        new Player($model2)
                    ]
                );

                return $this->redirectToRoute('tic_tac_toe_game');
            }

            if($game === 'tictactoe' && $typeOfProcess === 'cron') {
                for($i = 0; $i < $numberOfGames; $i++) {
                    $this->messageBus->dispatch(new TicTacToeGameCommand($model1, $model2));
                }
            }
        }

        return $this->render('tictactoe/setup_before_game.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tic-tac-toe', name: 'tic_tac_toe_game', methods: ['GET'])]
    public function ticTacToeGameStart(SessionInterface $session): Response {
        $gameService = new TicTacToeGameService(
            $session->get('players'),
            $this->ticTacToeGameRepository,
            $this->ticTacToeMoveRepository,
        );
        $session->set('board', $gameService->getBoard());
        $session->set('move_number', 0);

        $gameService->startGame();
        $session->set('game_id', $gameService->gameId);
        $modelsInfo = [];
        foreach ($gameService->getPlayers() as $player) {
            $model = $player->getModel();
            $modelsInfo[] = [
                "name" => $model->getName(),
                "imageName" => $model->getImageName(),
                "messages" => $model->getMessages(),
            ];
        }

        return $this->render('tictactoe/game_start.html.twig', [
            "models_info" => $modelsInfo,
            "board" => $gameService->getBoard()->board,
        ]);
    }

    #[Route('/tic-tac-toe/next-move', name: 'tic_tac_toe_next_move', methods: ['POST'])]
    public function ticTacToeNextMove(SessionInterface $session): JsonResponse {
        $gameService = new TicTacToeGameService(
            $session->get('players'),
            $this->ticTacToeGameRepository,
            $this->ticTacToeMoveRepository,
            $session->get('move_number'),
            $session->get('board'),
            $session->get('game_id'),
        );
        $gameService->nextMove();
        $session->set('board', $gameService->getBoard());
        $session->set('move_number', $gameService->getMoveCount());
        $board = $gameService->getBoard()->board;
        $modelsInfo = [];
        foreach ($gameService->getPlayers() as $player) {
            $model = $player->getModel();
            $modelsInfo[] = [
                "name" => $model->getName(),
                "imageName" => $model->getImageName(),
                "messages" => $model->getMessages(),
            ];
        }
        return new JsonResponse([
            "board" => $board,
            "models_info" => $modelsInfo,
        ]);
    }

    #[Route('/tic-tac-toe/all-game', name: 'tic_tac_toe_all_game', methods: ['POST'])]
    public function ticTacToeAllGame(SessionInterface $session): JsonResponse {
        $gameService = new TicTacToeGameService(
            $session->get('players'),
            $this->ticTacToeGameRepository,
            $this->ticTacToeMoveRepository,
        );
        $gameService->startGame();
        while($gameService->isGameOver() === false) {
            $gameService->nextMove();
        }
        $board = $gameService->getBoard()->board;
        foreach ($gameService->getPlayers() as $player) {
            $model = $player->getModel();
            $modelsInfo[] = [
                "name" => $model->getName(),
                "imageName" => $model->getImageName(),
                "messages" => $model->getMessages(),
            ];
        }
        return new JsonResponse([
            "board" => $board,
            "models_info" => $modelsInfo,
        ]);
    }
}
