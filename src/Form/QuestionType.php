<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
          
            ->add('text', TextType::class, [
                'label' => 'Texte de la question',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('point', IntegerType::class, [
                'label' => 'Points',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('ordreQuestion', IntegerType::class, [
                'label' => 'Ordre de la question',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
