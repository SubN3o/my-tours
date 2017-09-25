<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 19/06/17
 * Time: 17:15
 */

namespace MyOrleansBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CompleteSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('ville', SearchType::class, [
                'required'=>false,
                'attr'=> [
                    'id'=>'villes',
                    'placeholder'=>'Chercher une ville...',
                    'class'=>'autocomplete',
                    'autocomplete' => 'off'
                ]

            ])
//            ->add('quartier', SearchType::class, [
//                'required'=>false,
//                'attr'=> [
//                    'id'=>'quartiers',
//                    'placeholder'=>'Chercher un quartier...',
//                    'class'=>'autocomplete',
//                    'autocomplete' => 'off'
//                ]
//
//            ])
            ->add('type', ChoiceType::class, [
                'required'=>false,
                'placeholder'=>'Type du bien',
                'choices' => array(
                    'T1' => 'T1',
                    'T2' => 'T2',
                    'T3' => 'T3',
                    'T4' => 'T4',
                    'T5+' => 'T5+',
                )
            ])
//            ->add('surfaceMin', IntegerType::class, [
//                'required' => false,
//                'attr' => ['placeholder'=>'Surface min',
//                            'min' => '0'
//                            ],
//            ])
//            ->add('surfaceMax', IntegerType::class, [
//                'required' => false,
//                'attr' => ['placeholder'=>'Surface max',
//                    'min' => '0'
//                    ],
//            ])
//            ->add('nbChambres', ChoiceType::class, [
//                'required'=>false,
//                'placeholder'=>'Nb. Chambre(s)',
//                'choices' => array(
//                    '1 chambre' => '1',
//                    '2 chambres' => '2',
//                    '3 chambres' => '3',
//                    '4 chambres et plus' => '4',
//                )
//            ])

//            ->add('objectif', ChoiceType::class, [
//                'required'=>false,
//                'placeholder'=>'Objectif',
//                'choices' => array(
//                    'investir' => 'investir',
//                    'acheter en résidence principale' => 'Residence Principale',
//                )
//            ])
            ->add('budgetMin', IntegerType::class, [
                'scale' => 0,
                'required' => false,
            ])
            ->add('budgetMax', IntegerType::class, [
                'scale' => 0,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'waves-effect waves-light btn-large light-green']
            ])
            ->getForm();

    }

}