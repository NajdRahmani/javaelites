<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; // For the description
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // For Score Max
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [  // <---- CHANGE THIS FROM 'title' TO 'titre'
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Enter the title'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Enter the description'],
            ])
            ->add('scoreMax', IntegerType::class, [
                'label' => 'Score Max',
                'attr' => ['placeholder' => 'Enter the maximum score'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a maximum score.']),
                    new Positive(['message' => 'The score must be positive.']),
                ],
            ])
            ->add('dateLimite', DateType::class, [
                'label' => 'Date Limite',
                'widget' => 'single_text', // Use a single text input
                'format' => 'yyyy-MM-dd', // Or the desired date format
                'attr' => ['placeholder' => 'jj/mm/aaaa --:--'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // If you're mapping this to a Quiz entity, specify the class here:
            //'data_class' => App\Entity\Quiz::class,
        ]);
    }
}
