<?php

namespace MyToursBundle\Form;

use Doctrine\ORM\EntityRepository;
use MyToursBundle\Entity\Article;
use MyToursBundle\Entity\Evenement;
use MyToursBundle\Entity\Media;
use MyToursBundle\Entity\Pack;
use MyToursBundle\Entity\Partenaire;
use MyToursBundle\Entity\Residence;
use MyToursBundle\Entity\Service;
use MyToursBundle\Entity\TypeMedia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mediaFile', VichImageType::class,[
                'required' => false,
                'label' => 'Fichier média',
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => 'aperçu',

            ])
            ->add('typemedia', EntityType::class, [
                'class' => TypeMedia::class,
                'choice_label' => 'nom',
                'label' => 'Type de média',
            ])
        ;
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
        return 'mytoursbundle_media';
    }


}
