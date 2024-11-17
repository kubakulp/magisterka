<?php

namespace App\Form;

use App\AiChatModel\AiChatModelInterface;
use App\Service\AiChatModelLocator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameSetupType extends AbstractType
{
    public function __construct(
        private readonly AiChatModelLocator $modelLocator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var AiChatModelInterface[] $models
         */
        $models = $options['models'];

        $builder
            ->add('model1', ChoiceType::class, [
                'label' => 'First Player',
                'choices' => $models,
                'choice_label' => fn($model) => $model->getName(),
                'choice_value' => 'identifier',
                'required' => true,
            ])
            ->add('model2', ChoiceType::class, [
                'label' => 'Second Player',
                'choices' => $models,
                'choice_label' => fn($model) => $model->getName(),
                'choice_value' => 'identifier',
                'required' => true,
            ])
            ->add('game', ChoiceType::class, [
                'label' => 'Game',
                'choices' => [
                    'Tic Tac Toe' => 'tictactoe',
                    'Licitation' => 'licitation',
                ],
                'required' => true,
            ])
            ->add('typeOfProcess', ChoiceType::class, [
                'label' => 'Type of process',
                'choices' => [
                    'Play game in browser' => 'browser',
                    'Get data with cron' => 'cron',
                ],
                'required' => true,
            ])
            ->add('numberOfGames', IntegerType::class, [
                'label' => 'Number of games',
                'required' => true,
            ])
            ->add('start', SubmitType::class, [
                'label' => 'Rozpocznij grę',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'models' => $this->modelLocator->getModels(),
        ]);
    }
}
