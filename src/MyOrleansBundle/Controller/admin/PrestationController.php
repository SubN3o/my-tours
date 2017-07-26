<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Prestation;
use MyOrleansBundle\Form\PrestationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * prestation controller.
 *
 * @Route("admin/prestation")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PrestationController extends Controller
{
    /**
     * Lists all prestation entities.
     *
     * @Route("/", name="admin_prestation_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $prestations = $em->getRepository('MyOrleansBundle:Prestation')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $prestations,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('prestation/index.html.twig', array(
            'prestations' => $results,
        ));
    }

    /**
     * Creates a new prestation entity.
     *
     * @Route("/new", name="admin_prestation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $prestation = new Prestation();
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prestation);
            $em->flush();

            return $this->redirectToRoute('admin_prestation_show', array('id' => $prestation->getId()));
        }

        return $this->render('prestation/new.html.twig', array(
            'prestation' => $prestation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a prestation entity.
     *
     * @Route("/{id}", name="admin_prestation_show")
     * @Method("GET")
     */
    public function showAction(Prestation $prestation)
    {
        $deleteForm = $this->createDeleteForm($prestation);

        return $this->render('prestation/show.html.twig', array(
            'prestation' => $prestation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing prestation entity.
     *
     * @Route("/{id}/edit", name="admin_prestation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Prestation $prestation)
    {
        $deleteForm = $this->createDeleteForm($prestation);
        $editForm = $this->createForm('MyOrleansBundle\Form\PrestationType', $prestation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_prestation_index', array('id' => $prestation->getId()));
        }

        return $this->render('prestation/edit.html.twig', array(
            'prestation' => $prestation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a prestation entity.
     *
     * @Route("/{id}", name="admin_prestation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Prestation $prestation)
    {
        $form = $this->createDeleteForm($prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($prestation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_prestation_index');
    }

    /**
     * Creates a form to delete a prestation entity.
     *
     * @param Prestation $prestation The prestation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Prestation $prestation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_prestation_delete', array('id' => $prestation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
