<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Trimestre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Trimestre controller.
 *
 * @Route("admin/trimestre")
 * @Security("has_role('ROLE_ADMIN')")
 */
class TrimestreController extends Controller
{
    /**
     * Lists all trimestre entities.
     *
     * @Route("/", name="admin_trimestre_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $trimestres = $em->getRepository('MyOrleansBundle:Trimestre')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $trimestres,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('trimestre/index.html.twig', array(
            'trimestres' => $trimestres,
        ));
    }

    /**
     * Creates a new trimestre entity.
     *
     * @Route("/new", name="admin_trimestre_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $trimestre = new Trimestre();
        $form = $this->createForm('MyOrleansBundle\Form\TrimestreType', $trimestre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trimestre);
            $em->flush();

            $this->addFlash('success', 'Une nouveau trimestre a été ajouté');
            return $this->redirectToRoute('admin_trimestre_show', array('id' => $trimestre->getId()));
        }

        return $this->render('trimestre/new.html.twig', array(
            'trimestre' => $trimestre,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a trimestre entity.
     *
     * @Route("/{id}", name="admin_trimestre_show")
     * @Method("GET")
     */
    public function showAction(Trimestre $trimestre)
    {
        $deleteForm = $this->createDeleteForm($trimestre);

        return $this->render('trimestre/show.html.twig', array(
            'trimestre' => $trimestre,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing trimestre entity.
     *
     * @Route("/{id}/edit", name="admin_trimestre_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Trimestre $trimestre)
    {
        $deleteForm = $this->createDeleteForm($trimestre);
        $editForm = $this->createForm('MyOrleansBundle\Form\TrimestreType', $trimestre);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Ce trimestre a bien été mis à jour');
            return $this->redirectToRoute('admin_trimestre_edit', array('id' => $trimestre->getId()));
        }

        return $this->render('trimestre/edit.html.twig', array(
            'trimestre' => $trimestre,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a trimestre entity.
     *
     * @Route("/{id}", name="admin_trimestre_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Trimestre $trimestre)
    {
        $form = $this->createDeleteForm($trimestre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trimestre);
            $em->flush();
        }

        $this->addFlash('danger', 'Ce trimestre a été supprimé');
        return $this->redirectToRoute('admin_trimestre_index');
    }

    /**
     * Creates a form to delete a trimestre entity.
     *
     * @param Trimestre $trimestre The trimestre entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Trimestre $trimestre)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_trimestre_delete', array('id' => $trimestre->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
