<?php

namespace MyOrleansBundle\Form;

use MyOrleansBundle\Entity\Article;
use MyOrleansBundle\Entity\Evenement;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Partenaire;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\TypeMedia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mediaFile', FileType::class,[
                'required' => false,
                'label' => 'Fichier média'
            ])
            ->add('typemedia', EntityType::class, [
                'class' => TypeMedia::class,
                'choice_label' => 'nom',
                'label' => 'Type de média'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Media::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myorleansbundle_media';
    }


}
