<?php
/**
 * Created by PhpStorm.
 * User: HaGii
 * Date: 30/06/2017
 * Time: 11:24
 */

namespace MyOrleansBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class EvenementUploadListener
{

    private $uploader;
    private $oldMedia;

    public function __construct(FileUploader $uploader, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Media) {
            return;
        }
        if ($entity->getEvenements()) {
            $this->uploadFile($entity);

            if ($this->oldMedia) {
                $entity->setLien($this->oldMedia);
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Media) {
            return;
        }
        if ($entity->getEvenements()) {
            if (is_file($entity->getLien())) {
                unlink($entity->getLien());
            }
        }
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Media) {
            return;
        }


        if ($entity->getEvenements()) {
            $file = $entity->getLien();

            // only upload new files
            if ($file instanceof UploadedFile) {
                $fileName = $this->uploader->upload($file);
                $entity->setLien($fileName);
            }

        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Media) {
            return;
        }
        $masterRequest = $this->requestStack->getMasterRequest()->get('_route');
        if ($masterRequest == 'admin_evenement_edit') {

            if ($entity->getEvenements()) {

                $this->oldMedia = $entity->getLien();

                if ($fileName = $entity->getLien() && !$this->oldMedia) {
                    $entity->setLien(new File($this->uploader->getTargetDir() . '/' . $fileName));
                }

            }
        }

    }
}