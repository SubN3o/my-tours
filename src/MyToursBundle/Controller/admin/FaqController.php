<?php

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\Faq;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Faq controller.
 *
 * @Route("admin/faq")
 * @Security("has_role('ROLE_ADMIN')")
 */
class FaqController extends Controller
{
    /**
     * Lists all faq entities.
     *
     * @Route("/", name="admin_faq_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $faqs = $em->getRepository('MyToursBundle:Faq')->findBy([], ['tri'=>'ASC']);

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $faqs,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('faq/index.html.twig', array(
            'faqs' => $results,
        ));
    }

    /**
     * Creates a new faq entity.
     *
     * @Route("/new", name="admin_faq_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $faq = new Faq();
        $form = $this->createForm('MyToursBundle\Form\FaqType', $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($faq);
            $em->flush();

            $this->addFlash('success', 'Une nouvelle Q/R a été ajoutée');
            return $this->redirectToRoute('admin_faq_index', array('id' => $faq->getId()));
        }

        return $this->render('faq/new.html.twig', array(
            'faq' => $faq,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a faq entity.
     *
     * @Route("/{id}", name="admin_faq_show")
     * @Method("GET")
     */
    public function showAction(Faq $faq)
    {
        $deleteForm = $this->createDeleteForm($faq);

        return $this->render('faq/show.html.twig', array(
            'faq' => $faq,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing faq entity.
     *
     * @Route("/{id}/edit", name="admin_faq_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Faq $faq)
    {
        $deleteForm = $this->createDeleteForm($faq);
        $editForm = $this->createForm('MyToursBundle\Form\FaqType', $faq);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Cette Q/R a bien été mis à jour');
            return $this->redirectToRoute('admin_faq_index', array('id' => $faq->getId()));
        }

        return $this->render('faq/edit.html.twig', array(
            'faq' => $faq,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a faq entity.
     *
     * @Route("/{id}", name="admin_faq_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Faq $faq)
    {
        $form = $this->createDeleteForm($faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($faq);
            $em->flush();
        }

        $this->addFlash('danger', 'Cette Q/R a été supprimée');
        return $this->redirectToRoute('admin_faq_index');
    }

    /**
     * Creates a form to delete a faq entity.
     *
     * @param Faq $faq The faq entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Faq $faq)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_faq_delete', array('id' => $faq->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
