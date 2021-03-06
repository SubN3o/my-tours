<?php

namespace MyToursBundle\Form;

use MyToursBundle\Entity\TypeBien;
use MyToursBundle\Entity\TypeLogement;
use MyToursBundle\Entity\TypeMedia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, [
                'required' => true
            ])
            ->add('prix', IntegerType::class, [
                'attr' => ['min' => '0'],
                'required' => true
            ])
            ->add('surface', NumberType::class, [
                'required' => true
            ])
            ->add('surfaceSejour', NumberType::class, [
                'required' => false
            ])
            ->add('surfaceBalcon', NumberType::class, [
                'required' => false
            ])
            ->add('surfaceTerrasse', NumberType::class, [
                'required' => false
            ])
            ->add('surfaceJardin', NumberType::class, [
                'required' => false
            ])
            ->add('surfaceTerrain', NumberType::class, [
                'required' => false
            ])
            ->add('expositionSejour', ChoiceType::class, [
                'choices' =>[
                    'Choisir...' => null,
                    'Nord' => 'Nord',
                    'Nord Est' => 'Nord Est',
                    'Nord Ouest' => 'Nord Ouest',
                    'Sud' => 'Sud',
                    'Sud Est' => 'Sud Est',
                    'Sud Ouest' => 'Sud Ouest',
                    'Est' => 'Est',
                    'Ouest' => 'Ouest'],
                'expanded' => false,
                'multiple' => false,
                'required' =>false
          ])
            ->add('dateLivraison', ChoiceType::class, [
                'choices' => [
                    'Immédiate'=>'Immédiate',
                    '1er trimestre 2017' => '1er trimestre 2017',
                    '2ème trimestre 2017' => '2ème trimestre 2017',
                    '3ème trimestre 2017' => '3ème trimestre 2017',
                    '4ème trimestre 2017' => '4ème trimestre 2017',
                    '1er trimestre 2018' => '1er trimestre 2018',
                    '2ème trimestre 2018' => '2ème trimestre 2018',
                    '3ème trimestre 2018' => '3ème trimestre 2018',
                    '4ème trimestre 2018' => '4ème trimestre 2018',
                    '1er trimestre 2019' => '1er trimestre 2019',
                    '2ème trimestre 2019' => '2ème trimestre 2019',
                    '3ème trimestre 2019' => '3ème trimestre 2019',
                    '4ème trimestre 2019' => '4ème trimestre 2019',
                    '1er trimestre 2020' => '1er trimestre 2020',
                    '2ème trimestre 2020' => '2ème trimestre 2020',
                    '3ème trimestre 2020' => '3ème trimestre 2020',
                    '4ème trimestre 2020' => '4ème trimestre 2020',
                    '1er trimestre 2021' => '1er trimestre 2021',
                    '2ème trimestre 2021' => '2ème trimestre 2021',
                    '3ème trimestre 2021' => '3ème trimestre 2021',
                    '4ème trimestre 2021' => '4ème trimestre 2021',
                    '1er trimestre 2022' => '1er trimestre 2022',
                    '2ème trimestre 2022' => '2ème trimestre 2022',
                    '3ème trimestre 2022' => '3ème trimestre 2022',
                    '4ème trimestre 2022' => '4ème trimestre 2022',
                    '1er trimestre 2023' => '1er trimestre 2023',
                    '2ème trimestre 2023' => '2ème trimestre 2023',
                    '3ème trimestre 2023' => '3ème trimestre 2023',
                    '4ème trimestre 2023' => '4ème trimestre 2023',
                ],
                'placeholder' => 'Choisir...',
                'required' => false
            ])
            ->add('stationnement', ChoiceType::class, [
                'choices' =>[
                    'Choisir...' => null,
                    'Sans' => 'Sans',
                    '1 parking extérieur' => '1 parking extérieur',
                    '1 parking en sous sol' => '1 parking en sous sol',
                    '1 box' => '1 box',
                    '1 garage' => '1 garage',
                    '1 parking couvert' => '1 parking couvert',
                    '2 garages' => '2 garages',
                    '2 parkings extérieurs' => '2 parkings extérieurs',
                    '2 parkings en sous sol' => '2 parkings en sous sol',
                    '1 parking double en sous sol' => '1 parking double en sous sol',
                    '1 parking ext. & 1 parking en s/sol' => '1 parking ext. & 1 parking en s/sol',
                    '1 box & 1 parking couvert' => '1 box & 1 parking couvert',
                    '1 parking' => '1 parking',
                    '2 parkings' => '2 parkings',
                    '3 parkings' => '3 parkings'],
                'expanded' => false,
                'multiple' => false,
                'required' => false
            ])
            ->add('menuiserie', ChoiceType::class, [
                'choices' =>[
                    'PVC' => 'PVC',
                    'Aluminium' => 'Aluminium',
                    'Bois' => 'Bois',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('chauffage', ChoiceType::class, [
                'choices' =>[
                    'Choisir...' => null,
                    'Chaudière Gaz individuel' => 'Chaudière Gaz individuel',
                    'Chaudière Gaz collectif' => 'Chaudière Gaz collectif',
                    'Electrique individuel' => 'Electrique individuel',
                    'Electrique Gaz' => 'Electrique Gaz',
                    'Pompe à chaleur' => 'Pompe à chaleur',
                    'Chauffage Urbain' => 'Chauffage Urbain',
                    ],
                'expanded' => false,
                'multiple' => false,
                'required' => false
            ])
            ->add('solSejour', ChoiceType::class, [
                'choices' =>[
                    'Parquet' => 'Parquet',
                    'Vinyle' => 'Vinyle',
                    'Carrelage' => 'Carrelage',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('solSdb', ChoiceType::class, [
                'choices' =>[
                    'Carrelage' => 'Carrelage',
                    'Parquet' => 'Parquet',
                    'Vinyle' => 'Vinyle',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('solChambre', ChoiceType::class, [
                'choices' =>[
                    'Parquet' => 'Parquet',
                    'Vinyle' => 'Vinyle',
                    'Carrelage' => 'Carrelage',
                    ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('revetementMur', ChoiceType::class, [
                'choices' =>[
                    'Peinture' => 'Peinture',
                    'Papier peint' => 'Papier peint',
                    ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Disponible' => true,
                    'Vendu' => false,
                ]
            ])
            ->add('typeLogement', EntityType::class, [
                'class' => TypeLogement::class,
                'choice_label' => 'nom',
                'required' => true
            ])
            ->add('typeBien', EntityType::class, [
                'class' => TypeBien::class,
                'choice_label' => 'nom',
                'required' => true
            ])
            ->add('medias', CollectionType::class, [
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
                "attr" => [
                    "class" => "multiUpload"
                ]
            ])
            ->add('etage', TextType::class, [
                'required' => false
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyToursBundle\Entity\Flat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mytoursbundle_flat';
    }


}
