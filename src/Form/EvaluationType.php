<?php

namespace App\Form;

use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le titre de l\'évaluation (ex: Contrôle de Java - Chapitre 3)'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre est obligatoire'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Décrivez le contenu et les objectifs de l\'évaluation'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est obligatoire'
                    ])
                ]
            ])
            ->add('deadline', DateTimeType::class, [
                'label' => 'Date et heure limite',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'JJ/MM/AAAA HH:mm'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date limite est obligatoire'
                    ])
                ],
                'input' => 'datetime',
                'empty_data' => null
            ])
            ->add('badgeThreshold', IntegerType::class, [
                'label' => 'Score minimum pour obtenir un badge (sur 20)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 20,
                    'placeholder' => 'Ex: 15 sur 20'
                ]
            ])
            ->add('badgeTitle', TextType::class, [
                'label' => 'Titre du badge',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Badge d\'Excellence'
                ]
            ])
            ->add('badgeImage', ChoiceType::class, [
                'label' => 'Icône du badge',
                'required' => false,
                'choices' => [
                    '🏆 Trophée' => '🏆',
                    '🌟 Étoile' => '🌟',
                    '🎯 Cible' => '🎯',
                    '🎓 Diplôme' => '🎓',
                    '🏅 Médaille' => '🏅',
                    '👑 Couronne' => '👑',
                    '💫 Étoile filante' => '💫',
                    '🌈 Arc-en-ciel' => '🌈'
                ],
                'attr' => ['class' => 'form-control']
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
