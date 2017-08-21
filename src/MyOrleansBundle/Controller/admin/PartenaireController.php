<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Partenaire;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Partenaire controller.
 *
 * @Route("admin/partenaire")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PartenaireController extends Controller
{
    /**
     * Lists all partenaire entities.
     *
     * @Route("/", name="admin_partenaire_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $partenaires = $em->getRepository('MyOrleansBundle:Partenaire')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $partenaires,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('partenaire/index.html.twig', array(
            'partenaires' => $results,
        ));
    }

    /**
     * Creates a new partenaire entity.
     *
     * @Route("/new", name="admin_partenaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $partenaire = new Partenaire();
        $form = $this->createForm('MyOrleansBundle\Form\PartenaireType', $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Si l'administrateur n'upload pas de photo pour le partenaire, une photo est chargée par défaut
            $media = $partenaire->getMedia();
            if (is_null($media->getMediaName())) {
                /* @var $media Media */
                $media->setMediaName('default.jpg');
                $date = new \DateTimeImmutable();
                $media->setUpdatedAt($date);
            }

            $em->persist($partenaire);
            $em->flush();

            $this->addFlash('success', 'Ce partenaire a bien été ajouté');
            return $this->redirectToRoute('admin_partenaire_index', array('id' => $partenaire->getId()));
        }

        return $this->render('partenaire/new.html.twig', array(
            'partenaire' => $partenaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a partenaire entity.
     *
     * @Route("/{id}", name="admin_partenaire_show")
     * @Method("GET")
     */
    public function showAction(Partenaire $partenaire)
    {
        $deleteForm = $this->createDeleteForm($partenaire);

        return $this->render('partenaire/show.html.twig', array(
            'partenaire' => $partenaire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing partenaire entity.
     *
     * @Route("/{id}/edit", name="admin_partenaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Partenaire $partenaire, FileUploader $fileUploader)
    {
        $deleteForm = $this->createDeleteForm($partenaire);
        $editForm = $this->createForm('MyOrleansBundle\Form\PartenaireType', $partenaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // Si l'administrateur n'upload pas de photo pour le partenaire, une photo est chargée par défaut
            $media = $partenaire->getMedia();
            if (is_null($media->getMediaName())) {
                /* @var $media Media */
                $typeMediaImgCover = $em->getRepository(TypeMedia::class)->find(TypeMedia::IMAGE_COVER);
                $media->setTypeMedia($typeMediaImgCover);
                $media->setMediaName('default.jpg');
                $date = new \DateTimeImmutable();
                $media->setUpdatedAt($date);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Ce partenaire a bien été mis à jour');
            return $this->redirectToRoute('admin_partenaire_index', array('id' => $partenaire->getId()));
        }

        return $this->render('partenaire/edit.html.twig', array(
            'partenaire' => $partenaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a partenaire entity.
     *
     * @Route("/{id}", name="admin_partenaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Partenaire $partenaire)
    {
        $form = $this->createDeleteForm($partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($partenaire);
            $em->flush();
        }
        $this->addFlash('danger', 'Ce partenaire résidence a bien été supprimé');
        return $this->redirectToRoute('admin_partenaire_index');
    }

    /**
     * Deletes a partenaire media.
     *
     * @Route("/{id}/delete_media", name="partenaire_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Partenaire $partenaire)
    {
        $path = $partenaire->getMedia()->getMediaName();
        $em = $this->getDoctrine()->getManager();
        $partenaire->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_partenaire_edit', array('id' => $partenaire->getId()));
    }

    /**
     * Creates a form to delete a partenaire entity.
     *
     * @param Partenaire $partenaire The partenaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Partenaire $partenaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_partenaire_delete', array('id' => $partenaire->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
