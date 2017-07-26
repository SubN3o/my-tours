<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Quartier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Quartier controller.
 *
 * @Route("admin/quartier")
 * @Security("has_role('ROLE_ADMIN')")
 */
class QuartierController extends Controller
{
    /**
     * Lists all quartier entities.
     *
     * @Route("/", name="admin_quartier_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $quartiers = $em->getRepository('MyOrleansBundle:Quartier')->findAll();

        return $this->render('quartier/index.html.twig', array(
            'quartiers' => $quartiers,
        ));
    }

    /**
     * Creates a new quartier entity.
     *
     * @Route("/new", name="admin_quartier_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $quartier = new Quartier();
        $form = $this->createForm('MyOrleansBundle\Form\QuartierType', $quartier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quartier);
            $em->flush();

            $this->addFlash('success', 'Votre quartier a bien été ajouté');
            return $this->redirectToRoute('admin_quartier_index', array('id' => $quartier->getId()));
        }

        return $this->render('quartier/new.html.twig', array(
            'quartier' => $quartier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a quartier entity.
     *
     * @Route("/{id}", name="admin_quartier_show")
     * @Method("GET")
     */
    public function showAction(Quartier $quartier)
    {
        $deleteForm = $this->createDeleteForm($quartier);

        return $this->render('quartier/show.html.twig', array(
            'quartier' => $quartier,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing quartier entity.
     *
     * @Route("/{id}/edit", name="admin_quartier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Quartier $quartier)
    {
        $deleteForm = $this->createDeleteForm($quartier);
        $editForm = $this->createForm('MyOrleansBundle\Form\QuartierType', $quartier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre quartier a bien été mis à jour');
            return $this->redirectToRoute('admin_quartier_index', array('id' => $quartier->getId()));
        }

        return $this->render('quartier/edit.html.twig', array(
            'quartier' => $quartier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a quartier entity.
     *
     * @Route("/{id}", name="admin_quartier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Quartier $quartier)
    {
        $form = $this->createDeleteForm($quartier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quartier);
            $em->flush();
        }

        $this->addFlash('danger', 'Votre quartier a bien été mis à jour');
        return $this->redirectToRoute('admin_quartier_index');
    }

    /**
     * Creates a form to delete a quartier entity.
     *
     * @param Quartier $quartier The quartier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Quartier $quartier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_quartier_delete', array('id' => $quartier->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
