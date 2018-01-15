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
            ->add('prix', IntegerType::class, [
                'attr' => ['min' => '0'],
                'required' => true
            ])
            ->add('chargesAnnuelles', IntegerType::class, [
                'attr' => ['min' => '0'],
                'required' => false
            ])
            ->add('taxeFonciere', IntegerType::class, [
                'attr' => ['min' => '0'],
                'required' => true
            ])
            ->add('surface', NumberType::class, [
                'required' => true
            ])
            ->add('surfaceBalcon', NumberType::class, [
                'required' => false
            ])
            ->add('surfaceTerrasse', NumberType::class, [
                'required' => false
            ])
            ->add('surfaceJardin', NumberType::class, [
                'required' => false
            ])
            ->add('surfaceTerrain', NumberType::class, [
                'required' => false
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
            ->add('accroche', TextareaType::class, [
                "attr" => [
                    "class" => "materialize-textarea"
                ],
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                "attr" => [
                    "class" => "materialize-textarea"
                ]
            ])
            ->add('stationnement', ChoiceType::class, [
                'choices' =>[
                    'Choisir...' => null,
                    'Sans' => 'Sans',
                    '1 parking extérieur' => '1 parking extérieur',
                    '1 parking en sous sol' => '1 parking en sous sol',
                    '1 box' => '1 box',
                    '1 garage' => '1 garage',
                    '1 parking couvert' => '1 parking couvert',
                    '2 garages' => '2 garages',
                    '2 parkings extérieurs' => '2 parkings extérieurs',
                    '2 parkings en sous sol' => '2 parkings en sous sol',
                    '1 parking double en sous sol' => '1 parking double en sous sol',
                    '1 parking ext. & 1 parking en s/sol' => '1 parking ext. & 1 parking en s/sol',
                    '1 box & 1 parking couvert' => '1 box & 1 parking couvert',
                    '1 parking' => '1 parking',
                    '2 parkings' => '2 parkings',
                    '3 parkings' => '3 parkings'],
                'expanded' => false,
                'multiple' => false,
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
            ->add('chauffage', ChoiceType::class, [
                'choices' =>[
                    'Choisir...' => null,
                    'Chaudière Gaz individuel' => 'Chaudière Gaz individuel',
                    'Chaudière Gaz collectif' => 'Chaudière Gaz collectif',
                    'Electrique individuel' => 'Electrique individuel',
                    'Electrique Gaz' => 'Electrique Gaz',
                    'Pompe à chaleur' => 'Pompe à chaleur',
                    'Chauffage Urbain' => 'Chauffage Urbain',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false
            ])
            ->add('copropriete', ChoiceType::class, [
                'choices' => [
                    'oui' => true,
                    'non' => false,
                ]
            ])
            ->add('nbLots', IntegerType::class, [
                'attr' => ['min' => '0'],
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
