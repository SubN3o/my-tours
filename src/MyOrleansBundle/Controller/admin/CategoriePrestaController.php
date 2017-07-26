<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\CategoriePresta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * categoriepresta controller.
 *
 * @Route("admin/categorie-prestation")
 * @Security("has_role('ROLE_ADMIN')")
 */
class CategoriePrestaController extends Controller
{
    /**
     * Lists all categoriepresta entities.
     *
     * @Route("/", name="admin_categorie-presta_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categoriePrestas = $em->getRepository('MyOrleansBundle:CategoriePresta')->findAll();

        return $this->render('categoriepresta/index.html.twig', array(
            'categoriePrestas' => $categoriePrestas,
        ));
    }

    /**
     * Creates a new categoriepresta entity.
     *
     * @Route("/new", name="admin_categorie-presta_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $categoriepresta = new CategoriePresta();
        $form = $this->createForm('MyOrleansBundle\Form\CategoriePrestaType', $categoriepresta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoriepresta);
            $em->flush();

            return $this->redirectToRoute('admin_categorie-presta_show', array('id' => $categoriepresta->getId()));
        }

        return $this->render('categoriepresta/new.html.twig', array(
            'categoriepresta' => $categoriepresta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a categoriepresta entity.
     *
     * @Route("/{id}", name="admin_categorie-presta_show")
     * @Method("GET")
     */
    public function showAction(CategoriePresta $categoriepresta)
    {
        $deleteForm = $this->createDeleteForm($categoriepresta);

        return $this->render('categoriepresta/show.html.twig', array(
            'categoriePrestum' => $categoriepresta,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing categoriepresta entity.
     *
     * @Route("/{id}/edit", name="admin_categorie-presta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CategoriePresta $categoriepresta)
    {
        $deleteForm = $this->createDeleteForm($categoriepresta);
        $editForm = $this->createForm('MyOrleansBundle\Form\CategoriePrestaType', $categoriepresta);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_categorie-presta_index', array('id' => $categoriepresta->getId()));
        }

        return $this->render('categoriepresta/edit.html.twig', array(
            'categoriepresta' => $categoriepresta,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a categoriepresta entity.
     *
     * @Route("/{id}", name="admin_categorie-presta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CategoriePresta $categoriepresta)
    {
        $form = $this->createDeleteForm($categoriepresta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categoriepresta);
            $em->flush();
        }

        return $this->redirectToRoute('admin_categorie-presta_index');
    }

    /**
     * Deletes a categorie prestation media.
     *
     * @Route("/{id}/delete_media", name="categorie-presta_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMediaAction(CategoriePresta $categoriePresta)
    {
        $path = $categoriePresta->getMedia()->getLien();
        $em = $this->getDoctrine()->getManager();
        $categoriePresta->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_categorie-presta_edit', array('id' => $categoriePresta->getId()));
    }


    /**
     * Creates a form to delete a categoriepresta entity.
     *
     * @param CategoriePresta $categoriepresta The categoriepresta entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CategoriePresta $categoriepresta)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_categorie-presta_delete', array('id' => $categoriepresta->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
