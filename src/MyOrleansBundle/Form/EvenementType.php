<?php

namespace MyOrleansBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('adresse', TextType::class)
            ->add('codePostal', NumberType::class)
            ->add('ville', TextType::class)
            ->add('dateDebut', DateType::class, [
                'years' => range(date('Y') - 7, date('Y') + 7),
            ])
            ->add('dateFin', DateType::class, [
                'years' => range(date('Y') - 7, date('Y') + 7),
            ])
            ->add('heureDebut', TimeType::class)
            ->add('heureFin', TimeType::class)
            ->add('description', TextareaType::class,[
                "attr" => [
                    "class" => "materialize-textarea"
                ]
            ])
            ->add('medias', CollectionType::class,
                [
                    'entry_type' => MediaType::class,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => false,
                    "attr" => [
                        "class" => "multiUpload"
                    ]
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyOrleansBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myorleansbundle_evenement';
    }


}
