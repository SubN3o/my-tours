<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Chiffre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Chiffre controller.
 *
 * @Route("admin/chiffre")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ChiffreController extends Controller
{
    /**
     * Lists all chiffre entities.
     *
     * @Route("/", name="admin_chiffre_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $chiffres = $em->getRepository('MyOrleansBundle:Chiffre')->findBy([], ['tri'=>'ASC']);

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $chiffres,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('chiffre/index.html.twig', array(
            'chiffres' => $results,
        ));
    }

    /**
     * Creates a new chiffre entity.
     *
     * @Route("/new", name="admin_chiffre_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $chiffre = new Chiffre();
        $form = $this->createForm('MyOrleansBundle\Form\ChiffreType', $chiffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chiffre);
            $em->flush();

            $this->addFlash('success', 'Une nouveau chiffre a été ajoutée');
            return $this->redirectToRoute('admin_chiffre_index', array('id' => $chiffre->getId()));
        }

        return $this->render('chiffre/new.html.twig', array(
            'chiffre' => $chiffre,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a chiffre entity.
     *
     * @Route("/{id}", name="admin_chiffre_show")
     * @Method("GET")
     */
    public function showAction(Chiffre $chiffre)
    {
        $deleteForm = $this->createDeleteForm($chiffre);

        return $this->render('chiffre/show.html.twig', array(
            'chiffre' => $chiffre,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing chiffre entity.
     *
     * @Route("/{id}/edit", name="admin_chiffre_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Chiffre $chiffre)
    {
        $deleteForm = $this->createDeleteForm($chiffre);
        $editForm = $this->createForm('MyOrleansBundle\Form\ChiffreType', $chiffre);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Ce chiffre a bien été mis à jour');
            return $this->redirectToRoute('admin_chiffre_index', array('id' => $chiffre->getId()));
        }

        return $this->render('chiffre/edit.html.twig', array(
            'chiffre' => $chiffre,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a chiffre entity.
     *
     * @Route("/{id}", name="admin_chiffre_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Chiffre $chiffre)
    {
        $form = $this->createDeleteForm($chiffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chiffre);
            $em->flush();
        }

        $this->addFlash('danger', 'Ce chiffre a été supprimée');
        return $this->redirectToRoute('admin_chiffre_index');
    }

    /**
     * Creates a form to delete a chiffre entity.
     *
     * @param Chiffre $chiffre The chiffre entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chiffre $chiffre)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_chiffre_delete', array('id' => $chiffre->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
