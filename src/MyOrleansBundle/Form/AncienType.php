<?php

namespace MyOrleansBundle\Form;

use MyOrleansBundle\Entity\Quartier;
use MyOrleansBundle\Entity\TypeBien;
use MyOrleansBundle\Entity\TypeLogement;
use MyOrleansBundle\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AncienType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet', ChoiceType::class,[
                'choices'=>[
                    'Location meublé' => 'Location meublé',
                    'Location non meublé' => 'Location non meublé',
                    'Vente' => 'Vente'
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('etat', ChoiceType::class,[
                'choices'=>[
                    'Neuf' => 'Neuf',
                    'Récent (1-5ans)' => 'Récent',
                    'Ancien (>5ans)' => 'Ancien'
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('statut', ChoiceType::class,[
                'choices'=>[
                    'Disponible à la location' => 'Disponible à la location',
                    'Disponible à la vente' => 'Disponible à la vente',
                    'Loué' => 'Loué',
                    'Vendu' => 'Vendu'
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('prix', IntegerType::class, [
                'attr' => ['min' => '0'],
                'required' => true
            ])
            ->add('surface', NumberType::class, [
                'required' => true
            ])
            ->add('reference', TextType::class, [
                'required' => true
            ])
            ->add('ges', ChoiceType::class,[
                'choices'=>[
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G',
                    'Vierge' => 'Vierge'
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('energie', ChoiceType::class,[
                'choices'=>[
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G',
                    'Vierge' => 'Vierge'
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('description', TextareaType::class, [
                "attr" => [
                    "class" => "materialize-textarea"
                ]
            ])
            ->add('residence', TextType::class, [
                'required' => false
                ])
            ->add('medias', CollectionType::class, [
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'prototype' => true,
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
            ->add('date', DateType::class,[
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'],
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
            'data_class' => 'MyOrleansBundle\Entity\Ancien'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myorleansbundle_ancien';
    }


}
