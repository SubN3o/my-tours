<?php
/**
 * Created by PhpStorm.
 * User: wilder8
 * Date: 08/06/17
 * Time: 10:45
 */

namespace MyOrleansBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SimpleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('ville', SearchType::class, [
                'required'=>false,
                'attr'=> [
//                    'id'=>'autocomplete-input',
                    'class'=>'autocomplete',
                    'autocomplete' => 'off'
                ]
            ])

            ->add('type', ChoiceType::class, [
                'required'=>false,
                'choices' => array(
                    'T1' => 'T1',
                    'T2' => 'T2',
                    'T3' => 'T3',
                    'T4' => 'T4',
                    'T5+' => 'T5+',
                )
            ])

            ->add('search', SubmitType::class, [
                    'label'=>'GO',
                    'attr' => [
                        'class'=>'waves-effect waves-light btn white',
                        ]
                ])

            ->getForm();
    }
}