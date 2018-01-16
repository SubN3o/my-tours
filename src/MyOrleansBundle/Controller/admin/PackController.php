<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\TypeMedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Pack controller.
 *
 * @Route("admin/pack")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PackController extends Controller
{
    /**
     * Lists all pack entities.
     *
     * @Route("/", name="admin_pack_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $packs = $em->getRepository('MyOrleansBundle:Pack')->findBy([], ['tri'=>'ASC']);

        return $this->render('pack/index.html.twig', array(
            'packs' => $packs,
        ));
    }

    /**
     * Creates a new pack entity.
     *
     * @Route("/new", name="admin_pack_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pack = new Pack();
        $form = $this->createForm('MyOrleansBundle\Form\PackType', $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($pack);
            $em->flush();

            $this->addFlash('success', 'Votre pack a bien été ajouté');
            return $this->redirectToRoute('admin_pack_index', array('id' => $pack->getId()));
        }

        return $this->render('pack/new.html.twig', array(
            'pack' => $pack,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a pack entity.
     *
     * @Route("/{id}", name="admin_pack_show")
     * @Method("GET")
     */
    public function showAction(Pack $pack)
    {
        $deleteForm = $this->createDeleteForm($pack);

        return $this->render('pack/show.html.twig', array(
            'pack' => $pack,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing pack entity.
     *
     * @Route("/{id}/edit", name="admin_pack_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Pack $pack)
    {
        $deleteForm = $this->createDeleteForm($pack);
        $editForm = $this->createForm('MyOrleansBundle\Form\PackType', $pack);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre pack a bien été mis à jour');
            return $this->redirectToRoute('admin_pack_index', array('id' => $pack->getId()));
        }

        return $this->render('pack/edit.html.twig', array(
            'pack' => $pack,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a pack entity.
     *
     * @Route("/{id}", name="admin_pack_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Pack $pack)
    {
        $form = $this->createDeleteForm($pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pack);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre pack a bien été supprimé');
        return $this->redirectToRoute('admin_pack_index');
    }

    /**
     * Deletes a pack media.
     *
     * @Route("/{id}/delete_media", name="pack_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Pack $pack)
    {
        $path = $pack->getMedia()->getMediaName();
        $em = $this->getDoctrine()->getManager();
        $pack->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_pack_edit', array('id' => $pack->getId()));
    }
    /**
     * Creates a form to delete a pack entity.
     *
     * @param Pack $pack The pack entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pack $pack)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_pack_delete', array('id' => $pack->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
