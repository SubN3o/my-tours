<?php

namespace MyOrleansBundle\Form;

use MyOrleansBundle\Entity\Collaborateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollaborateurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required'=>false])
            ->add('prenom', TextType::class)
            ->add('fonction', TextType::class)
            ->add('bio', TextareaType::class,[
                "attr" => [
                    "class" => "materialize-textarea"
                ]
            ])
            ->add('lienFacebook', UrlType::class, [
                'required'=>false])
            ->add('lienLinkedin', UrlType::class, [
                'required'=>false])
            ->add('email', EmailType::class)
            ->add('media', MediaType::class,[
                "attr" => [
                    "class" => "upload"
                ]
            ])
            ->add('tri', IntegerType::class, [
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
            'data_class' => Collaborateur::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myorleansbundle_collaborateur';
    }


}
