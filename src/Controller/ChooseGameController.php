<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChooseGameController extends AbstractController
{

    #[Route('/select-game', name: 'select_game', methods: ['GET', 'POST'])]
    public function selectGame(Request $request, SessionInterface $session): Response
    {
        $form = $this->createFormBuilder()
            ->add('game', ChoiceType::class, [
                'choices' => [
                    'Tic-Tac-Toe' => 'tictactoe',
                    'Licitation' => 'licitation',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Wybierz grę'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Dalej'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData()['game'];
            $session->set('selected_game', $game);

            if($game === 'tictactoe') {
                return $this->redirectToRoute('setup_before_game');
            }

            if($game === 'licitation') {
                return $this->redirectToRoute('licitation_setup_before_game');
            }
        }

        return $this->render('game/select_game.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
