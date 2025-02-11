<?php
// src/Form/QUIZQUESTIONType.php
namespace App\Form;

use App\Entity\QUIZQUESTION;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QUIZQUESTIONType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('points')
            ->add('texte')
            ->add('ordre')
            ->add('reponses', CollectionType::class, [
                'entry_type' => QuizResponseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'label' => 'Responses',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('add_another', SubmitType::class, [
                'label' => 'Add Another Question',
                'attr' => ['class' => 'btn btn-secondary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QUIZQUESTION::class,
        ]);
    }
}