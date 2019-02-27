<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 02/10/2017
 * Time: 11:08
 */

namespace MyToursBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SimpleSearchMobileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('ville', SearchType::class, [
                'required'=>false,
                'attr'=> [
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