<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('text', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control ',
                    'rows' => 8
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image (en format .jpg ou .png)', 
                'required' => true,
                // 'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'maxSizeMessage' => 'Fichier trop lourd, 2048ko maximum',
                        'mimeTypesMessage' => 'Veuillez uploader en format jpg ou png',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('Ajouter', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
