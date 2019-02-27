<?php

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\TypeArticle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Typearticle controller.
 *
 * @Route("admin/typearticle")
 * @Security("has_role('ROLE_ADMIN')")
 */
class TypeArticleController extends Controller
{
    /**
     * Lists all typeArticle entities.
     *
     * @Route("/", name="admin_typearticle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeArticles = $em->getRepository('MyToursBundle:TypeArticle')->findAll();

        return $this->render('typearticle/index.html.twig', array(
            'typeArticles' => $typeArticles,
        ));
    }

    /**
     * Creates a new typeArticle entity.
     *
     * @Route("/new", name="admin_typearticle_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeArticle = new Typearticle();
        $form = $this->createForm('MyToursBundle\Form\TypeArticleType', $typeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeArticle);
            $em->flush();

            $this->addFlash('success', 'Un nouveau type article a bien été ajouté');
            return $this->redirectToRoute('admin_typearticle_index', array('id' => $typeArticle->getId()));
        }

        return $this->render('typearticle/new.html.twig', array(
            'typeArticle' => $typeArticle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeArticle entity.
     *
     * @Route("/{id}", name="admin_typearticle_show")
     * @Method("GET")
     */
    public function showAction(TypeArticle $typeArticle)
    {
        $deleteForm = $this->createDeleteForm($typeArticle);

        return $this->render('typearticle/show.html.twig', array(
            'typeArticle' => $typeArticle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeArticle entity.
     *
     * @Route("/{id}/edit", name="admin_typearticle_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeArticle $typeArticle)
    {
        $deleteForm = $this->createDeleteForm($typeArticle);
        $editForm = $this->createForm('MyToursBundle\Form\TypeArticleType', $typeArticle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le type article a bien été mis à jour');
            return $this->redirectToRoute('admin_typearticle_index', array('id' => $typeArticle->getId()));
        }

        return $this->render('typearticle/edit.html.twig', array(
            'typeArticle' => $typeArticle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeArticle entity.
     *
     * @Route("/{id}", name="admin_typearticle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeArticle $typeArticle)
    {
        $form = $this->createDeleteForm($typeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeArticle);
            $em->flush();
        }
        $this->addFlash('danger', 'Le type article a bien été supprimé');
        return $this->redirectToRoute('admin_typearticle_index');
    }

    /**
     * Creates a form to delete a typeArticle entity.
     *
     * @param TypeArticle $typeArticle The typeArticle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeArticle $typeArticle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_typearticle_delete', array('id' => $typeArticle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
