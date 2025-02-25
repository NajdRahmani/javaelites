<?php

namespace App\Form;

use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Entrez le titre de l\'évaluation',
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez l\'évaluation en détail',
                    'class' => 'form-control',
                    'rows' => 4
                ]
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Date Limite',
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d')
                ]
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
