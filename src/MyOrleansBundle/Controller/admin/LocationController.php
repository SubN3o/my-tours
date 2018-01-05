<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Location;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Form\LocationType;
use MyOrleansBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Location controller.
 *
 * @Route("admin/location")
 * @Security("has_role('ROLE_ADMIN')")
 */
class LocationController extends Controller
{
    /**
     * Lists all location entities.
     *
     * @Route("/", name="admin_location_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $locations = $em->getRepository('MyOrleansBundle:Location')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $locations,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('location/index.html.twig', array(
            'locations' => $results,
        ));
    }

    /**
     * Creates a new location entity.
     *
     * @Route("/new", name="admin_location_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $location = new Location();
        $media = new Media();
        $location->getMedias()->add($media);

        $form = $this->createForm('MyOrleansBundle\Form\LocationType', $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($location);
            $em->flush();

            $this->addFlash('success', 'Votre location a bien été ajoutée');
            return $this->redirectToRoute('admin_location_index', array('id' => $location->getId()));
        }

        return $this->render('location/new.html.twig', array(
            'location' => $location,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a location entity.
     *
     * @Route("/{id}", name="admin_location_show")
     * @Method("GET")
     */
    public function showAction(Location $location)
    {
        $deleteForm = $this->createDeleteForm($location);

        return $this->render('location/show.html.twig', array(
            'location' => $location,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing location entity.
     *
     * @Route("/{id}/edit", name="admin_location_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Location $location)
    {
        $deleteForm = $this->createDeleteForm($location);
        if (!empty($location->getMedias()->isEmpty())) {
            $media = new Media();
            $location->getMedias()->add($media);
        }

        $editForm = $this->createForm(LocationType::class, $location);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre location a bien été mis à jour');
            return $this->redirectToRoute('admin_location_index', array('id' => $location->getId()));
        }

        return $this->render('location/edit.html.twig', array(
            'location' => $location,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a location entity.
     *
     * @Route("/{id}", name="admin_location_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Location $location)
    {
        $form = $this->createDeleteForm($location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($location);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre location a bien été supprimée');

        return $this->redirectToRoute('admin_location_index');
    }


    /**
     * Deletes a location media.
     *
     * @Route("/{id}/delete_media/{media_id}", name="location_media_delete")
     * @ParamConverter("location", class="MyOrleansBundle:Location", options={"id" = "id"})
     * @ParamConverter("media", class="MyOrleansBundle:Media", options={"id" = "media_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Location $location, Media $media)
    {
        //$locations = $media->getLocations();
        $em = $this->getDoctrine()->getManager();

        $path = $media->getmediaName();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        $location->removeMedia($media);
        $em->remove($media);

        $em->flush();
        return $this->redirectToRoute('admin_location_edit', array('id' => $location->getId()));
    }


    /**
     * Creates a form to delete a location entity.
     *
     * @param Location $location The location entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Location $location)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_location_delete', array('id' => $location->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
