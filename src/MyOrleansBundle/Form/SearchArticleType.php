<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 28/11/2017
 * Time: 16:08
 */

namespace MyOrleansBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('keyword', SearchType::class, [
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Veuillez saisir un mot-clé',
                    'autocomplete'=>'off'
                ]
            ])

            ->getForm();

    }
}