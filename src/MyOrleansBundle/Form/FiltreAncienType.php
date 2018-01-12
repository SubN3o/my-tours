<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 12/01/2018
 * Time: 09:10
 */

namespace MyOrleansBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FiltreAncienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('filter', ChoiceType::class, [
                'choices' => array(
                    'Prix croissant' => 1,
                    'Prix décroissant' => 2,
                    'Surface croissante' => 3,
                    'Surface décroissante' => 4
                ),
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Trier par ...',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Trier',
                'attr' => ['class' => 'waves-effect waves-light btn greenMyO']
            ])
            ->getForm();
    }
}