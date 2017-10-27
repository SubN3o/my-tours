<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Evenement;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Evenement controller.
 *
 * @Route("admin/evenement")
 * @Security("has_role('ROLE_ADMIN')")
 */
class EvenementController extends Controller
{
    /**
     * Lists all evenement entities.
     *
     * @Route("/", name="admin_evenement_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('MyOrleansBundle:Evenement')->findBy([], ['dateDebut'=>'ASC']);

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $evenements,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('evenement/index.html.twig', array(
            'evenements' => $results,
        ));
    }

    /**
     * Creates a new evenement entity.
     *
     * @Route("/new", name="admin_evenement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $evenement = new Evenement();
        $form = $this->createForm('MyOrleansBundle\Form\EvenementType', $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Si l'administrateur n'upload pas de photo pour l'evenement, une photo est chargée par défaut
            $media = $evenement->getMedia();
            if (is_null($media->getMediaName())) {
                /* @var $media Media */
                $media->setMediaName('default.jpg');
                $date = new \DateTimeImmutable();
                $media->setUpdatedAt($date);
            }

            $em->persist($evenement);
            $em->flush();

            $this->addFlash('success', 'Votre evenement a bien été ajouté');
            return $this->redirectToRoute('admin_evenement_index', array('id' => $evenement->getId()));
        }

        return $this->render('evenement/new.html.twig', array(
            'evenement' => $evenement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a evenement entity.
     *
     * @Route("/{id}", name="admin_evenement_show")
     * @Method("GET")
     */
    public function showAction(Evenement $evenement)
    {
        $deleteForm = $this->createDeleteForm($evenement);

        return $this->render('evenement/show.html.twig', array(
            'evenement' => $evenement,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing evenement entity.
     *
     * @Route("/{id}/edit", name="admin_evenement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Evenement $evenement, FileUploader $fileUploader)
    {
        $deleteForm = $this->createDeleteForm($evenement);
        $editForm = $this->createForm('MyOrleansBundle\Form\EvenementType', $evenement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // Si l'administrateur n'upload pas de photo pour l'evenement, une photo est chargée par défaut
            $media = $evenement->getMedia();
                if (is_null($media->getMediaName())) {
                    /* @var $media Media */
                    $typeMediaImgCover = $em->getRepository(TypeMedia::class)->find(TypeMedia::IMAGE_COVER);
                    $media->setTypeMedia($typeMediaImgCover);
                    $media->setMediaName('default.jpg');
                    $date = new \DateTimeImmutable();
                    $media->setUpdatedAt($date);
                }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre evenement a bien été mis à jour');
            return $this->redirectToRoute('admin_evenement_index', array('id' => $evenement->getId()));
        }

        return $this->render('evenement/edit.html.twig', array(
            'evenement' => $evenement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a evenement entity.
     *
     * @Route("/{id}", name="admin_evenement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Evenement $evenement)
    {
        $form = $this->createDeleteForm($evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evenement);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre evenement a bien été supprimé');
        return $this->redirectToRoute('admin_evenement_index');
    }

    /**
     * Deletes a evenement media.
     *
     * @Route("/{id}/delete_media", name="evenement_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Evenement $evenement)
    {
        $path = $evenement->getMedia()->getMediaName();
        $em = $this->getDoctrine()->getManager();
        $evenement->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_evenement_edit', array('id' => $evenement->getId()));
    }
    /**
     * Creates a form to delete a evenement entity.
     *
     * @param Evenement $evenement The evenement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Evenement $evenement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_evenement_delete', array('id' => $evenement->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
