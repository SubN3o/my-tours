<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 19/06/17
 * Time: 17:15
 */

namespace MyOrleansBundle\Form;


use MyOrleansBundle\Entity\TypeBien;
use MyOrleansBundle\Entity\TypeLogement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->setMethod('POST')
            ->add('ville', SearchType::class, [
                'required'=>false,
                'attr'=> [
//                    'id'=>'villes',
                    'placeholder'=>'Chercher une ville...',
                    'class'=>'autocomplete',
                    'autocomplete' => 'off'
                ]

            ])

//            ->add('typeLogement', ChoiceType::class, [
//                'expanded' => true,
//                'multiple' => false,
//                'required'=>false,
//                'placeholder'=>'Aucun',
//                'choices' => array(
//                    'T1' => 'T1',
//                    'T2' => 'T2',
//                    'T3' => 'T3',
//                    'T4' => 'T4',
//                    'T5+' => 'T5+',
//                )
//            ])
            ->add('typeLogement', EntityType::class, [
                'class' => TypeLogement::class,
                'choice_label' => 'nom',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])


            ->add('budgetMin', MoneyType::class, [
                'scale' => 0,
                'required' => false,
                'grouping'=> true,
                'attr' => ['min' => '0',
                           'placeholder' => '50 000'
                ],
            ])
            ->add('budgetMax', MoneyType::class, [
                'scale' => 0,
                'required' => false,
                'grouping'=> true,
                'attr' => ['min' => '0',
                            'placeholder' => '300 000'
                            ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'waves-effect waves-light btn greenMyO']
            ])
            ->getForm();

    }

}