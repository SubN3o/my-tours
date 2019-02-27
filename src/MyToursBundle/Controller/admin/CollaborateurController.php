<?php

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\Collaborateur;
use MyToursBundle\Entity\Media;
use MyToursBundle\Entity\TypeMedia;
use MyToursBundle\Form\CollaborateurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Collaborateur controller.
 *
 * @Route("admin/collaborateur")
 * @Security("has_role('ROLE_ADMIN')")
 */
class CollaborateurController extends Controller
{
    /**
     * Lists all collaborateur entities.
     *
     * @Route("/", name="admin_collaborateur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collaborateurs = $em->getRepository('MyToursBundle:Collaborateur')->findBy([], ['tri'=>'ASC']);

        return $this->render('collaborateur/index.html.twig', array(
            'collaborateurs' => $collaborateurs,
        ));
    }

    /**
     * Creates a new collaborateur entity.
     *
     * @Route("/new", name="admin_collaborateur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $collaborateur = new Collaborateur();
        $form = $this->createForm(CollaborateurType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($collaborateur);
            $em->flush();

            $this->addFlash('success', 'Un nouveau collaborateur a bien été ajouté');
            return $this->redirectToRoute('admin_collaborateur_index', array('id' => $collaborateur->getId()));
        }

        return $this->render('collaborateur/new.html.twig', array(
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a collaborateur entity.
     *
     * @Route("/{id}", name="admin_collaborateur_show")
     * @Method("GET")
     */
    public function showAction(Collaborateur $collaborateur)
    {
        $deleteForm = $this->createDeleteForm($collaborateur);

        return $this->render('collaborateur/show.html.twig', array(
            'collaborateur' => $collaborateur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing collaborateur entity.
     *
     * @Route("/{id}/edit", name="admin_collaborateur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Collaborateur $collaborateur)
    {
        $deleteForm = $this->createDeleteForm($collaborateur);

        $editForm = $this->createForm(CollaborateurType::class, $collaborateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Ce collaborateur a bien été mis à jour');
            return $this->redirectToRoute('admin_collaborateur_index', array('id' => $collaborateur->getId()));
        }

        return $this->render('collaborateur/edit.html.twig', array(
            'collaborateur' => $collaborateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a collaborateur entity.
     *
     * @Route("/{id}", name="admin_collaborateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Collaborateur $collaborateur)
    {
        $form = $this->createDeleteForm($collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($collaborateur);
            $em->flush();
        }
        $this->addFlash('danger', 'Ce collaborateur a bien été supprimé');
        return $this->redirectToRoute('admin_collaborateur_index');
    }

    /**
     * Deletes a collaborateur media.
     *
     * @Route("/{id}/delete_media", name="collaborateur_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Collaborateur $collaborateur)
    {
        $path = $collaborateur->getMedia()->getMediaName();
        $em = $this->getDoctrine()->getManager();
        $collaborateur->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_collaborateur_edit', array('id' => $collaborateur->getId()));
    }

    /**
     * Creates a form to delete a collaborateur entity.
     *
     * @param Collaborateur $collaborateur The collaborateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Collaborateur $collaborateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_collaborateur_delete', array('id' => $collaborateur->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
