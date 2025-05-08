<?php

namespace App\Controller;

use App\AiChatModel\AiChatModelInterface;
use App\Command\LicitationGameCommand;
use App\Form\LicitationGameSetupType;
use App\Licitation\LicitationGameService;
use App\Repository\LicitationAnswerRepository;
use App\Repository\LicitationGameRepository;
use App\Repository\LicitationItemRepository;
use App\Repository\LicitationMoveRepository;
use App\Repository\LicitationPlayerRepository;
use App\Service\AiChatModelLocator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class LicitationController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $eventBus,
        private LicitationGameRepository $licitationGameRepository,
        private LicitationPlayerRepository $licitationPlayerRepository,
        private LicitationItemRepository $licitationItemRepository,
        private LicitationMoveRepository $licitationMoveRepository,
        private LicitationAnswerRepository $licitationAnswerRepository,
    ){
    }

    #[Route('/licitation-setup', name: 'licitation_setup_before_game', methods: ['GET', 'POST'])]
    public function setupBeforeGame(Request $request, AiChatModelLocator $modelLocator, SessionInterface $session): Response
    {
        $models = $modelLocator->getModels();

        $form = $this->createForm(LicitationGameSetupType::class, null, [
            'models' => $models,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typeOfProcess = $form->get('typeOfProcess')->getData();
            $numberOfGames = $form->get('number_of_games')->getData();
            /**
             * @var AiChatModelInterface[] $models
             */
            $models = $form->get('chat_types')->getData();
            $items = $request->request->all()['items'] ?? [];
            $numberOfRepeats = $form->get('number_of_repeats')->getData();
            $promptType = $form->get('promptType')->getData();
            $numberOfTokens = $form->get('number_of_tokens')->getData();
            $session->set('models', $models);
            $session->set('items', $items);
            $session->set('promptType', $promptType);
            $session->set('number_of_repeats', $numberOfRepeats);
            $session->set('number_of_tokens', $numberOfTokens);
            if($typeOfProcess === 'browser') {
                return $this->redirectToRoute('licitation_game');
            }

            if($typeOfProcess === 'cron') {
                for($i = 0; $i < $numberOfGames; $i++) {
                    $this->eventBus->dispatch(new LicitationGameCommand(
                        $models,
                        $items,
                        $promptType,
                        $numberOfRepeats,
                        $numberOfTokens
                    ));
                }
            }
        }

        return $this->render('licitation/setup_before_game.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/licitation', name: 'licitation_game', methods: ['GET'])]
    public function licitationGameStart(SessionInterface $session): Response {
        $gameService = new LicitationGameService(
            $session->get('models'),
            $session->get('items'),
            $this->licitationGameRepository,
            $this->licitationAnswerRepository,
            $this->licitationItemRepository,
            $this->licitationMoveRepository,
            $this->licitationPlayerRepository,
            $session->get('promptType'),
            $session->get('number_of_repeats'),
            $session->get('number_of_tokens'),
        );
        $gameService->startGame();
        $modelsInfo = [];
        foreach ($gameService->getPlayers() as $player) {
            $model = $player->getModel();
            $modelsInfo[] = [
                "name" => $model->getName(),
                "imageName" => $model->getImageName(),
                "messages" => $model->getMessages(),
            ];
        }

        return $this->render('licitation/game_start.html.twig', [
            "models_info" => $modelsInfo,
        ]);
    }
}
