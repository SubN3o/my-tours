<?php

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\Media;
use MyToursBundle\Entity\Service;
use MyToursBundle\Entity\TypeMedia;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

        $services = $em->getRepository('MyToursBundle:Service')->findBy([], ['tri'=>'ASC']);

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
    public function newAction(Request $request)
    {
        $service = new Service();
        $media = new Media();
        $service->getMedias()->add($media);

        $form = $this->createForm('MyToursBundle\Form\ServiceType', $service);
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
    public function editAction(Request $request, Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);
        if (!empty($service->getMedias()->isEmpty())) {
            $media = new Media();
            $service->getMedias()->add($media);
        }
        $editForm = $this->createForm('MyToursBundle\Form\ServiceType', $service);
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
     * @Route("/{id}/delete_media/{media_id}", name="service_media_delete")
     * @ParamConverter("service", class="MyToursBundle:Service", options={"id" = "id"})
     * @ParamConverter("media", class="MyToursBundle:Media", options={"id" = "media_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Service $service, Media $media)
    {
        $em = $this->getDoctrine()->getManager();

        $path = $media->getmediaName();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        $service->removeMedia($media);
        $em->remove($media);

        $em->flush();
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
