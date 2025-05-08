<?php

namespace App\Form;

use App\AiChatModel\AiChatModelInterface;
use App\Core\LicitationPromptType;
use App\Service\AiChatModelLocator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LicitationGameSetupType extends AbstractType
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
            ->add('chat_types', ChoiceType::class, [
                'choices' => $models,
                'choice_label' => fn($model) => $model->getName(),
                'choice_value' => 'identifier',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Wybierz typy czatu',
            ])
            ->add('number_of_games', IntegerType::class, [
                'label' => 'Liczba gier',
                'attr' => ['min' => 1],
            ])
            ->add('number_of_repeats', IntegerType::class, [
                'label' => 'Liczba powtórzeń',
                'attr' => ['min' => 1],
            ])
            ->add('number_of_tokens', IntegerType::class, [
                'label' => 'Liczba tokenów',
                'attr' => ['min' => 1],
            ])
            ->add('typeOfProcess', ChoiceType::class, [
                'label' => 'Type of process',
                'choices' => [
                    'Play game in browser' => 'browser',
                    'Get data with queue' => 'cron',
                ],
                'required' => true,
            ])
            ->add('number_of_items', IntegerType::class, [
                'label' => 'Liczba przedmiotów',
                'attr' => ['min' => 1, 'class' => 'item-count'],
            ])
            ->add('items', CollectionType::class, [
                'entry_type' => NumberType::class,
                'allow_add' => true,
                'entry_options' => ['attr' => ['class' => 'item-value']],
                'prototype' => true,
            ])
            ->add('promptType', ChoiceType::class, [
                'label' => 'Prompt type',
                'choices' => LicitationPromptType::cases(),
                'choice_value' => fn (?LicitationPromptType $choice) => $choice?->value,
                'choice_label' => fn (LicitationPromptType $choice) => $choice->name,
                'required' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'models' => $this->modelLocator->getModels(),
        ]);
    }
}
