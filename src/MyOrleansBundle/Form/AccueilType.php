<?php

namespace MyOrleansBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccueilType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('presentation', TextareaType::class)
                ->add('mentions', TextareaType::class, [
                    "attr" => [
                        "class" => "materialize ckeditor"
                    ]
                ])
                ->add('medias', CollectionType::class,
                    [
                      'entry_type' => MediaType::class,
                       'allow_add' => true,
                        'prototype' => true,
                     'by_reference' => false
                    ])
                ->add('medias', CollectionType::class,
                [
                    'entry_type' => MediaType::class,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => false
                ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyOrleansBundle\Entity\Accueil'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myorleansbundle_accueil';
    }


}
