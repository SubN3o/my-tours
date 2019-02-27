<?php

namespace MyToursBundle\Form;

use MyToursBundle\Entity\Media;
use MyToursBundle\Entity\NormeThermique;
use MyToursBundle\Entity\Quartier;
use MyToursBundle\Entity\TypeMedia;
use MyToursBundle\Entity\Ville;
use MyToursBundle\Entity\Zone;
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
            ->add('description', TextareaType::class, [
                "attr" => [
                    "class" => "materialize-textarea"
                ],
                'required' => false,
            ])
            ->add('nbTotalLogements', IntegerType::class, [
                'attr' => ['min' => '0'],
                'required' => false
            ])
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
            ])
            ->add('accroche', TextareaType::class, [
                "attr" => [
                    "class" => "materialize-textarea"
                ],
                'required' => false
            ])
            ->add('accroche2', TextareaType::class, [
                "attr" => [
                    "class" => "materialize-textarea"
                ],
                'required' => false
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
            )
            ->add('tri', IntegerType::class, [
                'required' => false
            ])
            ->add('lienYoutube', TextType::class, [
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyToursBundle\Entity\Residence',

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mytoursbundle_residence';
    }


}
