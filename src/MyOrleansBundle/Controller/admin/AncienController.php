<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Ancien;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Form\AncienType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Ancien controller.
 *
 * @Route("admin/ancien")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AncienController extends Controller
{
    /**
     * Lists all ancien entities.
     *
     * @Route("/", name="admin_ancien_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $anciens = $em->getRepository('MyOrleansBundle:Ancien')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $anciens,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('ancien/index.html.twig', array(
            'anciens' => $results,
        ));
    }

    /**
     * Creates a new ancien entity.
     *
     * @Route("/new", name="admin_ancien_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ancien = new Ancien();
        $media = new Media();
        $ancien->getMedias()->add($media);

        $form = $this->createForm('MyOrleansBundle\Form\AncienType', $ancien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($ancien);
            $em->flush();

            $this->addFlash('success', 'Votre bien ancien a bien été ajoutée');
            return $this->redirectToRoute('admin_ancien_index', array('id' => $ancien->getId()));
        }

        return $this->render('ancien/new.html.twig', array(
            'ancien' => $ancien,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ancien entity.
     *
     * @Route("/{id}", name="admin_ancien_show")
     * @Method("GET")
     */
    public function showAction(Ancien $ancien)
    {
        $deleteForm = $this->createDeleteForm($ancien);

        return $this->render('ancien/show.html.twig', array(
            'ancien' => $ancien,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ancien entity.
     *
     * @Route("/{id}/edit", name="admin_ancien_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Ancien $ancien)
    {
        $deleteForm = $this->createDeleteForm($ancien);
        if (!empty($ancien->getMedias()->isEmpty())) {
            $media = new Media();
            $ancien->getMedias()->add($media);
        }

        $editForm = $this->createForm(AncienType::class, $ancien);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre bien ancien a bien été mis à jour');
            return $this->redirectToRoute('admin_ancien_index', array('id' => $ancien->getId()));
        }

        return $this->render('ancien/edit.html.twig', array(
            'ancien' => $ancien,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ancien entity.
     *
     * @Route("/{id}", name="admin_ancien_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ancien $ancien)
    {
        $form = $this->createDeleteForm($ancien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ancien);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre bien ancien a bien été supprimée');

        return $this->redirectToRoute('admin_ancien_index');
    }


    /**
     * Deletes a ancien media.
     *
     * @Route("/{id}/delete_media/{media_id}", name="ancien_media_delete")
     * @ParamConverter("ancien", class="MyOrleansBundle:Ancien", options={"id" = "id"})
     * @ParamConverter("media", class="MyOrleansBundle:Media", options={"id" = "media_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Ancien $ancien, Media $media)
    {
        //$anciens = $media->getAnciens();
        $em = $this->getDoctrine()->getManager();

        $path = $media->getmediaName();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        $ancien->removeMedia($media);
        $em->remove($media);

        $em->flush();
        return $this->redirectToRoute('admin_ancien_edit', array('id' => $ancien->getId()));
    }


    /**
     * Creates a form to delete a ancien entity.
     *
     * @param Ancien $ancien The ancien entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ancien $ancien)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ancien_delete', array('id' => $ancien->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
