<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 08/01/2018
 * Time: 17:27
 */

namespace MyOrleansBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FiltreLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('filter', ChoiceType::class, [
//                'required' => false,
                'choices' => array(
                    'Loyer' => 'loyer',
                    'Surface' => 'surface',
                ),
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => ['class' => 'waves-effect waves-light btn greenMyO']
            ])
            ->getForm();
    }
}