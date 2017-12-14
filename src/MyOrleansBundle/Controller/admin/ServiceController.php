<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Service controller.
 *
 * @Route("admin/service")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ServiceController extends Controller
{
    /**
     * Lists all service entities.
     *
     * @Route("/", name="admin_service_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $services = $em->getRepository('MyOrleansBundle:Service')->findBy([], ['tri'=>'ASC']);

        return $this->render('service/index.html.twig', array(
            'services' => $services,
        ));
    }

    /**
     * Creates a new service entity.
     *
     * @Route("/new", name="admin_service_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $service = new Service();
        $form = $this->createForm('MyOrleansBundle\Form\ServiceType', $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($service);
            $em->flush();

            $this->addFlash('success', 'Votre service a bien été ajouté');
            return $this->redirectToRoute('admin_service_index', array('id' => $service->getId()));
        }

        return $this->render('service/new.html.twig', array(
            'service' => $service,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a service entity.
     *
     * @Route("/{id}", name="admin_service_show")
     * @Method("GET")
     */
    public function showAction(Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);

        return $this->render('service/show.html.twig', array(
            'service' => $service,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing service entity.
     *
     * @Route("/{id}/edit", name="admin_service_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Service $service, FileUploader $fileUploader)
    {
        $deleteForm = $this->createDeleteForm($service);
        $editForm = $this->createForm('MyOrleansBundle\Form\ServiceType', $service);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre service a bien été mis à jour');
            return $this->redirectToRoute('admin_service_index', array('id' => $service->getId()));
        }

        return $this->render('service/edit.html.twig', array(
            'service' => $service,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a service entity.
     *
     * @Route("/{id}", name="admin_service_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Service $service)
    {
        $form = $this->createDeleteForm($service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre service a bien été supprimé');
        return $this->redirectToRoute('admin_service_index');
    }

    /**
     * Deletes a service media.
     *
     * @Route("/{id}/delete_media", name="service_media_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Service $service)
    {
        $path = $service->getMedia()->getMediaName();
        $em = $this->getDoctrine()->getManager();
        $service->setMedia(null);
        $em->flush();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        return $this->redirectToRoute('admin_service_edit', array('id' => $service->getId()));
    }
    /**
     * Creates a form to delete a service entity.
     *
     * @param Service $service The service entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Service $service)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_service_delete', array('id' => $service->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
