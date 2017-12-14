<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Plaquette;
use MyOrleansBundle\Entity\TypeMedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Plaquette controller.
 *
 * @Route("admin/plaquette")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PlaquetteController extends Controller
{
    /**
     * Lists all plaquette entities.
     *
     * @Route("/", name="admin_plaquette_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $plaquettes = $em->getRepository('MyOrleansBundle:Plaquette')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $plaquettes,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('plaquette/index.html.twig', array(
            'plaquettes' => $results,
        ));
    }

    /**
     * Creates a new plaquette entity.
     *
     * @Route("/new", name="admin_plaquette_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $plaquette = new Plaquette();
        $form = $this->createForm('MyOrleansBundle\Form\PlaquetteType', $plaquette);

        //On charge le champ typemedia avec le type PDF par defaut
        $em = $this->getDoctrine()->getManager();
        $typeMediaPdf = $em->getRepository(TypeMedia::class)->find(TypeMedia::PDF);
        $form->get('media')->get('typemedia')->setData($typeMediaPdf);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($plaquette);
            $em->flush();

            $this->addFlash('success', 'Votre témoignage a bien été ajouté');
            return $this->redirectToRoute('admin_plaquette_index', array('id' => $plaquette->getId()));
        }

        return $this->render('plaquette/new.html.twig', array(
            'plaquette' => $plaquette,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a plaquette entity.
     *
     * @Route("/{id}", name="admin_plaquette_show")
     * @Method("GET")
     */
    public function showAction(Plaquette $plaquette)
    {
        $deleteForm = $this->createDeleteForm($plaquette);

        return $this->render('plaquette/show.html.twig', array(
            'plaquette' => $plaquette,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing plaquette entity.
     *
     * @Route("/{id}/edit", name="admin_plaquette_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Plaquette $plaquette)
    {
        $deleteForm = $this->createDeleteForm($plaquette);
        $editForm = $this->createForm('MyOrleansBundle\Form\PlaquetteType', $plaquette);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre plaquette a bien été mis à jour');
            return $this->redirectToRoute('admin_plaquette_index', array('id' => $plaquette->getId()));
        }

        return $this->render('plaquette/edit.html.twig', array(
            'plaquette' => $plaquette,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a plaquette entity.
     *
     * @Route("/{id}", name="admin_plaquette_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Plaquette $plaquette)
    {
        $form = $this->createDeleteForm($plaquette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($plaquette);
            $em->flush();
        }

        $this->addFlash('danger', 'Votre plaquette a bien été supprimé');
        return $this->redirectToRoute('admin_plaquette_index');
    }

    /**
     * Deletes a plaquette media.
     *
     * @Route("/{id}/delete_media", name="plaquette_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Plaquette $plaquette)
    {
        $path = $plaquette->getMedia()->getMediaName();
        $em = $this->getDoctrine()->getManager();
        $plaquette->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_plaquette_edit', array('id' => $plaquette->getId()));
    }

    /**
     * Creates a form to delete a plaquette entity.
     *
     * @param Plaquette $plaquette The plaquette entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Plaquette $plaquette)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_plaquette_delete', array('id' => $plaquette->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
