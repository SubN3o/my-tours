<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Ville controller.
 *
 * @Route("admin/ville")
 * @Security("has_role('ROLE_ADMIN')")
 */
class VilleController extends Controller
{
    /**
     * Lists all ville entities.
     *
     * @Route("/", name="admin_ville_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $villes = $em->getRepository('MyOrleansBundle:Ville')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $villes,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('ville/index.html.twig', array(
            'villes' => $results,
        ));
    }

    /**
     * Creates a new ville entity.
     *
     * @Route("/new", name="admin_ville_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ville = new Ville();
        $form = $this->createForm('MyOrleansBundle\Form\VilleType', $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'Une nouvelle ville a été ajoutée');
            return $this->redirectToRoute('admin_ville_index', array('id' => $ville->getId()));
        }

        return $this->render('ville/new.html.twig', array(
            'ville' => $ville,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ville entity.
     *
     * @Route("/{id}", name="admin_ville_show")
     * @Method("GET")
     */
    public function showAction(Ville $ville)
    {
        $deleteForm = $this->createDeleteForm($ville);

        return $this->render('ville/show.html.twig', array(
            'ville' => $ville,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ville entity.
     *
     * @Route("/{id}/edit", name="admin_ville_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Ville $ville)
    {
        $deleteForm = $this->createDeleteForm($ville);
        $editForm = $this->createForm('MyOrleansBundle\Form\VilleType', $ville);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Cette ville a bien été mis à jour');
            return $this->redirectToRoute('admin_ville_index', array('id' => $ville->getId()));
        }

        return $this->render('ville/edit.html.twig', array(
            'ville' => $ville,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ville entity.
     *
     * @Route("/{id}", name="admin_ville_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ville $ville)
    {
        $form = $this->createDeleteForm($ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ville);
            $em->flush();
        }

        $this->addFlash('danger', 'Cette ville a été supprimée');
        return $this->redirectToRoute('admin_ville_index');
    }

    /**
     * Creates a form to delete a ville entity.
     *
     * @param Ville $ville The ville entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ville $ville)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ville_delete', array('id' => $ville->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
