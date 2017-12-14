<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Realisation;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Realisation controller.
 *
 * @Route("admin/realisation")
 * @Security("has_role('ROLE_ADMIN')")
 */
class RealisationController extends Controller
{
    /**
     * Lists all realisation entities.
     *
     * @Route("/", name="admin_realisation_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $realisations = $em->getRepository('MyOrleansBundle:Realisation')->findBy([], ['tri'=>'ASC']);

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $realisations,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('realisation/index.html.twig', array(
            'realisations' => $results,
        ));
    }

    /**
     * Creates a new realisation entity.
     *
     * @Route("/new", name="admin_realisation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $realisation = new Realisation();
        $form = $this->createForm('MyOrleansBundle\Form\RealisationType', $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($realisation);
            $em->flush();

            $this->addFlash('success', 'Cette réalisation a bien été ajouté');
            return $this->redirectToRoute('admin_realisation_index', array('id' => $realisation->getId()));
        }

        return $this->render('realisation/new.html.twig', array(
            'realisation' => $realisation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a realisation entity.
     *
     * @Route("/{id}", name="admin_realisation_show")
     * @Method("GET")
     */
    public function showAction(Realisation $realisation)
    {
        $deleteForm = $this->createDeleteForm($realisation);

        return $this->render('realisation/show.html.twig', array(
            'realisation' => $realisation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing realisation entity.
     *
     * @Route("/{id}/edit", name="admin_realisation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Realisation $realisation, FileUploader $fileUploader)
    {
        $deleteForm = $this->createDeleteForm($realisation);
        $editForm = $this->createForm('MyOrleansBundle\Form\RealisationType', $realisation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Cette réalisation a bien été mis à jour');
            return $this->redirectToRoute('admin_realisation_index', array('id' => $realisation->getId()));
        }

        return $this->render('realisation/edit.html.twig', array(
            'realisation' => $realisation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a realisation entity.
     *
     * @Route("/{id}", name="admin_realisation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Realisation $realisation)
    {
        $form = $this->createDeleteForm($realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($realisation);
            $em->flush();
        }
        $this->addFlash('danger', 'Cette réalisation résidence a bien été supprimé');
        return $this->redirectToRoute('admin_realisation_index');
    }

    /**
     * Deletes a realisation media.
     *
     * @Route("/{id}/delete_media", name="realisation_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Realisation $realisation)
    {
        $path = $realisation->getMedia()->getMediaName();
        $em = $this->getDoctrine()->getManager();
        $realisation->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_realisation_edit', array('id' => $realisation->getId()));
    }

    /**
     * Creates a form to delete a realisation entity.
     *
     * @param Realisation $realisation The realisation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Realisation $realisation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_realisation_delete', array('id' => $realisation->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
