<?php
/**
 * Created by PhpStorm.
 * User: jean-baptiste
 * Date: 19/06/17
 * Time: 17:47
 */

namespace MyOrleansBundle\Form;


use MyOrleansBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('post')
            ->add('civilite', ChoiceType::class,[
                'expanded' => true,
                'multiple' => false,
                'data' => 'Mr',
                'choices' => [
                    'Mr'=>'Mr',
                    'Mme'=>'Mme'
                ]
            ])
            ->add('nom', TextType::class,[
                'required' => true
            ])
            ->add('email', EmailType::class,[
                'required' => true
            ])
            ->add('telephone', TextType::class,[
//                'required' => true
            ])
            ->add('codePostal', IntegerType::class,[
//                'required' => true
            ])
            ->add('projet', ChoiceType::class,[
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Habiter'=>'Habiter',
                    'Investir' => 'Investir'
                ],
                'data' => 'Habiter'
            ])
            ->add('typeLogement', ChoiceType::class,[
                'expanded' => true,
                'multiple' => true,
                'choices'=>[
                    'T1'=>'T1',
                    'T2'=>'T2',
                    'T3'=>'T3',
                    'T4'=>'T4',
                    'T5+'=>'T5+',
                    ],
                'required' => false
            ])
            ->add('budget', IntegerType::class,[
                'required' => false
            ])
            ->add('sujet', TextType::class)
            ->add('message', TextareaType::class, [
                'attr' =>['class' => 'materialize-textarea'],
                'required' => true
            ])
            ->add('newsletter', ChoiceType::class, [
                'choices' => array('oui' => true, 'non' => false),
                'data' => true,
                'expanded' => true,
                'multiple' => false
            ])

            ->add('envoyer', SubmitType::class, [
                'attr'=> ['class' => 'waves-effect waves-light btn-large greenMyO']
            ])
            ->getForm();

    }
}

