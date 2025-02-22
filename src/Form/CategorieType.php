<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('descrption')
            ->add('brochure', FileType::class, [
                'label' => 'Upload File',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword', // DOC
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
                            'image/jpeg', // JPEG
                            'image/png', // PNG
                        ],
                        'mimeTypesMessage' => 'Please upload a valid file (PDF, DOC, DOCX, JPEG, or PNG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
