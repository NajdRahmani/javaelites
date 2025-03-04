<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Enter the title'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Enter the description'],
            ])
            ->add('scoreMax', IntegerType::class, [
                'label' => 'Score Max',
                'attr' => ['placeholder' => 'Enter the maximum score'],
            ])
            ->add('dateLimite', DateType::class, [
                'label' => 'Date Limite',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['placeholder' => 'jj/mm/aaaa --:--'],
                'required' => false,
                'empty_data' => null,
            ])
            ->add('recaptcha', HiddenType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please complete the reCAPTCHA']),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Passer le quiz'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
