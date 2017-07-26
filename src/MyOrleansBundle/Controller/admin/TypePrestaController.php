<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\TypePresta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**

 * Typepresta controller.
 *
 * @Route("admin/typepresta")
 * @Security("has_role('ROLE_ADMIN')")
 */
class TypePrestaController extends Controller
{
    /**
     * Lists all typePresta entities.
     *
     * @Route("/", name="admin_typepresta_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typePrestas = $em->getRepository('MyOrleansBundle:TypePresta')->findAll();

        return $this->render('typepresta/index.html.twig', array(
            'typePrestas' => $typePrestas,
        ));
    }

    /**
     * Creates a new typePresta entity.
     *
     * @Route("/new", name="admin_typepresta_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typePresta = new TypePresta();
        $form = $this->createForm('MyOrleansBundle\Form\TypePrestaType', $typePresta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typePresta);
            $em->flush();

            return $this->redirectToRoute('admin_typepresta_show', array('id' => $typePresta->getId()));
        }

        return $this->render('typepresta/new.html.twig', array(
            'typePresta' => $typePresta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typePresta entity.
     *
     * @Route("/{id}", name="admin_typepresta_show")
     * @Method("GET")
     */
    public function showAction(TypePresta $typePresta)
    {
        $deleteForm = $this->createDeleteForm($typePresta);

        return $this->render('typepresta/show.html.twig', array(
            'typePresta' => $typePresta,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typePresta entity.
     *
     * @Route("/{id}/edit", name="admin_typepresta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypePresta $typePresta)
    {
        $deleteForm = $this->createDeleteForm($typePresta);
        $editForm = $this->createForm('MyOrleansBundle\Form\TypePrestaType', $typePresta);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_typepresta_index', array('id' => $typePresta->getId()));
        }

        return $this->render('typepresta/edit.html.twig', array(
            'typePresta' => $typePresta,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typePresta entity.
     *
     * @Route("/{id}", name="admin_typepresta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypePresta $typePresta)
    {
        $form = $this->createDeleteForm($typePresta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typePresta);
            $em->flush();
        }

        return $this->redirectToRoute('admin_typepresta_index');
    }

    /**
     * Creates a form to delete a typePresta entity.
     *
     * @param TypePresta $typePresta The typePresta entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypePresta $typePresta)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_typepresta_delete', array('id' => $typePresta->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
