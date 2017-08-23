<?php

namespace MyOrleansBundle\Form;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\NormeThermique;
use MyOrleansBundle\Entity\Quartier;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Entity\Ville;
use MyOrleansBundle\Entity\Zone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResidenceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
            ])
            ->add('adresse', TextType::class, [
                'required' => true,
            ])
            ->add('codePostal', IntegerType::class, [
                'required' => true,
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'required' => true

            ])
            ->add('quartier',  EntityType::class, [
                'class' => Quartier::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir...',
                'required' => false
            ])
            ->add('normeThermique', ChoiceType::class, [
                'choices' => [
                    'RT2012' => 'RT2012',
                    'BBC' => 'BBC',
                ],
            ])
            ->add('zone', ChoiceType::class, [
                'choices' => [
                    'B1' => 'B1',
                    'A' => 'A',
                    'B2' => 'B2',
                    'C' => 'C',
                ],
            ])
            ->add('dateLivraison', ChoiceType::class, [
                'choices' => [
                    '1er trimestre 2017' => '1er trimestre 2017',
                    '2nd trimestre 2017' => '2nd trimestre 2017',
                    '3e trimestre 2017' => '3e trimestre 2017',
                    '4e trimestre 2017' => '4e trimestre 2017',
                    '1er trimestre 2018' => '1er trimestre 2018',
                    '2nd trimestre 2018' => '2nd trimestre 2018',
                    '3e trimestre 2018' => '3e trimestre 2018',
                    '4e trimestre 2018' => '4e trimestre 2018',
                    '1er trimestre 2019' => '1er trimestre 2019',
                    '2nd trimestre 2019' => '2nd trimestre 2019',
                    '3e trimestre 2019' => '3e trimestre 2019',
                    '4e trimestre 2019' => '4e trimestre 2019',
                ],
                'placeholder' => 'Choisir...',
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('nbTotalLogements', NumberType::class, [
                'required' => false])
            ->add('noteTransports', NumberType::class, [
                'required' => false
            ])
            ->add('noteCommerces', NumberType::class, [
                'required' => false
            ])
            ->add('noteServices', NumberType::class, [
                'required' => false
            ])
            ->add('noteEsthetisme', NumberType::class, [
                'required' => false
            ])
            ->add('offre', TextType::class, [
                'required' => false
            ])
            ->add('favoris', ChoiceType::class, [
                'choices' => [
//                    'Définir en résidence favorite' => null,
                    'Oui' => true,
                    'Non' => false],
                'required' => false,
                'placeholder' => 'Définir en résidence favorite'
            ])
            ->add('affichagePrix',ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false]
            ])
            ->add('eligibilitePinel',ChoiceType::class, [
                'choices' => [
                    'Choisir...' => null,
                    'Oui' => true,
                    'Non' => false],
                'required' => false

            ])
            ->add('accroche', TextareaType::class, [
                'required' => false
            ])
            ->add('accroche2', TextareaType::class, [
                'required' => false
            ])
            ->add('medias', CollectionType::class,
                [
                    'entry_type' => MediaType::class,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => false
                ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyOrleansBundle\Entity\Residence',

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myorleansbundle_residence';
    }


}
