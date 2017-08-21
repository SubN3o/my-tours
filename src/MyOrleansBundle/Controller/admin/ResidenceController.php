<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Form\ResidenceType;
use MyOrleansBundle\Service\FileUploader;
use MyOrleansBundle\Service\Geoloc;
use MyOrleansBundle\Service\MyOrleans_Twig_Extension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Residence controller.
 *
 * @Route("/admin/residence")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ResidenceController extends Controller
{
    /**
     * Lists all residence entities.
     *
     * @Route("/", name="admin_residence_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $residences = $em->getRepository('MyOrleansBundle:Residence')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $residences,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('residence/index.html.twig', array(
            'residences' => $results,
        ));
    }

    /**
     * Creates a new residence entity.
     *
     * @Route("/new", name="admin_residence_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Geoloc $geoloc)
    {

        $residence = new Residence();
        $media = new Media();
        $residence->getMedias()->add($media);
        $form = $this->createForm(ResidenceType::class, $residence);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // gestion des coord gps uniquement si l'adresse est saisie
            if (!empty($residence->getAdresse()) &&
                !empty($residence->getCodePostal()) &&
                !empty($residence->getVille())) {

                try {
                    $geoloc->updateCoord($residence, $this->getParameter('GoogleApiKey'));
                } catch (\RuntimeException $e) {
                    $this->addFlash('danger', $e->getMessage() . ' Les coordonnées GPS ne seront pas mises à jour.');
                }
            }

            // Si l'administrateur n'upload pas de photo pour la résidence, une photo est chargée par défaut
            $medias = $residence->getMedias();
            foreach ($medias as $media){
                if (is_null($media->getMediaName())) {
                    /* @var $media Media */
                    $media->setMediaName('default.jpg');
                    $date = new \DateTimeImmutable();
                    $media->setUpdatedAt($date);
                }
            }

            $em->persist($residence);
            $em->flush();

            $this->addFlash('success', 'Votre résidence a bien été ajoutée');
            return $this->redirectToRoute('admin_residence_index');

        }



        return $this->render('residence/new.html.twig', array(
            'residence' => $residence,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a residence entity.
     *
     * @Route("/{id}", name="admin_residence_show")
     * @Method("GET")
     */
    public function showAction(Residence $residence)
    {
        $deleteForm = $this->createDeleteForm($residence);

        return $this->render('residence/show.html.twig', array(
            'residence' => $residence,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing residence entity.
     *
     * @Route("/{id}/edit", name="admin_residence_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Residence $residence, Geoloc $geoloc)
    {
        $deleteForm = $this->createDeleteForm($residence);
        if ($residence->getMedias()->isEmpty()) {
            $media = new Media();
            $residence->getMedias()->add($media);
        }
        $editForm = $this->createForm(ResidenceType::class, $residence);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            try {
                // on maj les coord gps dans tous les cas
                $geoloc->updateCoord($residence, $this->getParameter('GoogleApiKey'));
            } catch (\RuntimeException $e) {
                $this->addFlash('danger', $e->getMessage() . ' Les coordonnées GPS ne seront pas mises à jour.');
            }

            // Si l'administrateur n'upload pas de photo pour la résidence, une photo est chargée par défaut
            $medias = $residence->getMedias();
            foreach ($medias as $media){
                if (is_null($media->getMediaName())) {
                    /* @var $media Media */
                    $typeMediaImgCover = $em->getRepository(TypeMedia::class)->find(TypeMedia::IMAGE_COVER);
                    $media->setTypeMedia($typeMediaImgCover);
                    $media->setMediaName('default.jpg');
                    $date = new \DateTimeImmutable();
                    $media->setUpdatedAt($date);
                }
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre résidence a bien été mis à jour');
            return $this->redirectToRoute('admin_residence_index', array('id' => $residence->getId()));
        }


        return $this->render('residence/edit.html.twig', array(
            'residence' => $residence,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a residence entity.
     *
     * @Route("/{id}", name="admin_residence_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Residence $residence)
    {
        $form = $this->createDeleteForm($residence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($residence);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre résidence a bien été supprimée');
        return $this->redirectToRoute('admin_residence_index');
    }

    /**
     * Deletes a residence media.
     *
     * @Route("/{id}/delete_media/{media_id}", name="residence_media_delete")
     * @ParamConverter("residence", class="MyOrleansBundle:Residence", options={"id" = "id"})
     * @ParamConverter("media", class="MyOrleansBundle:Media", options={"id" = "media_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Residence $residence, Media $media)
    {
        $em = $this->getDoctrine()->getManager();

        $path = $media->getMediaName();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        $residence->removeMedia($media);
        $em->remove($media);

        $em->flush();
        return $this->redirectToRoute('admin_residence_edit', array('id' => $residence->getId()));
    }


    /**
     * Creates a form to delete a residence entity.
     *
     * @param Residence $residence The residence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Residence $residence)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_residence_delete', array('id' => $residence->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
