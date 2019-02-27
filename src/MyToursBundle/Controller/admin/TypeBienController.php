<?php

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\TypeBien;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Typebien controller.
 *
 * @Route("admin/typebien")
 * @Security("has_role('ROLE_ADMIN')")
 */
class TypeBienController extends Controller
{
    /**
     * Lists all typeBien entities.
     *
     * @Route("/", name="typebien_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeBiens = $em->getRepository('MyToursBundle:TypeBien')->findAll();

        return $this->render('typebien/index.html.twig', array(
            'typeBiens' => $typeBiens,
        ));
    }

    /**
     * Creates a new typeBien entity.
     *
     * @Route("/new", name="typebien_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeBien = new Typebien();
        $form = $this->createForm('MyToursBundle\Form\TypeBienType', $typeBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeBien);
            $em->flush();

            $this->addFlash('success', 'Un nouveau type de bien a été ajouté');
            return $this->redirectToRoute('typebien_index', array('id' => $typeBien->getId()));
        }

        return $this->render('typebien/new.html.twig', array(
            'typeBien' => $typeBien,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeBien entity.
     *
     * @Route("/{id}", name="typebien_show")
     * @Method("GET")
     */
    public function showAction(TypeBien $typeBien)
    {
        $deleteForm = $this->createDeleteForm($typeBien);

        return $this->render('typebien/show.html.twig', array(
            'typeBien' => $typeBien,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeBien entity.
     *
     * @Route("/{id}/edit", name="typebien_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeBien $typeBien)
    {
        $deleteForm = $this->createDeleteForm($typeBien);
        $editForm = $this->createForm('MyToursBundle\Form\TypeBienType', $typeBien);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le type de bien a été mis à jour');
            return $this->redirectToRoute('typebien_index', array('id' => $typeBien->getId()));
        }

        return $this->render('typebien/edit.html.twig', array(
            'typeBien' => $typeBien,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeBien entity.
     *
     * @Route("/{id}", name="typebien_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeBien $typeBien)
    {
        $form = $this->createDeleteForm($typeBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeBien);
            $em->flush();
        }
        $this->addFlash('danger', 'Le type de bien résidence a bien été supprimé');
        return $this->redirectToRoute('typebien_index');
    }

    /**
     * Creates a form to delete a typeBien entity.
     *
     * @param TypeBien $typeBien The typeBien entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeBien $typeBien)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typebien_delete', array('id' => $typeBien->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
