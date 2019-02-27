<?php

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\Flat;
use MyToursBundle\Entity\Media;
use MyToursBundle\Entity\Plaquette;
use MyToursBundle\Entity\Residence;
use MyToursBundle\Entity\TypeMedia;
use MyToursBundle\Form\FlatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Flat controller.
 *
 * @Route("admin/flat")
 * @Security("has_role('ROLE_ADMIN')")
 */
class FlatController extends Controller
{
    /**
     * Lists all flat entities.
     *
     * @Route("/list/{id}", name="admin_flat_index")
     * @Method("GET")
     */
    public function indexAction(Residence $residence, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $flats = $em->getRepository('MyToursBundle:Flat')->findByResidence($residence, ['reference'=>'ASC']);

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $flats,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('flat/index.html.twig', array(
            'flats' => $results,
            'residence' => $residence,
        ));
    }

    /**
     * Creates a new flat entity.
     *
     * @Route("/new/{id}", name="admin_flat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Residence $residence, Request $request)
    {
        $flat = new Flat();
        $media = new Media();
        $flat->getMedias()->add($media);
        $form = $this->createForm(FlatType::class, $flat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $flat->setResidence($residence);

            $em->persist($flat);
            $em->flush();

            $this->addFlash('success', 'Votre appartement a bien été ajoutée');
            return $this->redirectToRoute('admin_flat_index', array('id' => $flat->getResidence()->getId()));
        }

        return $this->render('flat/new.html.twig', array(
            'flat' => $flat,
            'residence' => $residence,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a flat entity.
     *
     * @Route("/{id}", name="admin_flat_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Flat $flat)
    {
        $deleteForm = $this->createDeleteForm($flat);

        return $this->render('flat/show.html.twig', [
                'flat' => $flat,
                'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to delete a flat entity.
     *
     * @param Flat $flat The flat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Flat $flat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_flat_delete', array('id' => $flat->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing flat entity.
     *
     * @Route("/{id}/edit", name="admin_flat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Flat $flat)
    {
        $deleteForm = $this->createDeleteForm($flat);
        if ($flat->getMedias()->isEmpty()) {
            $media = new Media();
            $flat->getMedias()->add($media);
        }
        $editForm = $this->createForm('MyToursBundle\Form\FlatType', $flat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre appartement a bien été mis à jour');
            return $this->redirectToRoute('admin_flat_index', array('id' => $flat->getResidence()->getId()));
        }

        return $this->render('flat/edit.html.twig', array(
            'flat' => $flat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a flat entity.
     *
     * @Route("/{id}", name="admin_flat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Flat $flat)
    {
        $form = $this->createDeleteForm($flat);
        $form->handleRequest($request);
        $residence_id = $flat->getResidence()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($flat);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre appartement a bien été supprimée');
        return $this->redirectToRoute('admin_flat_index', ['id' => $residence_id]);
    }

    /**
     * Deletes a flat media.
     *
     * @Route("/{id}/delete_media/{media_id}", name="flat_media_delete")
     * @ParamConverter("flat", class="MyToursBundle:Flat", options={"id" = "id"})
     * @ParamConverter("media", class="MyToursBundle:Media", options={"id" = "media_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Flat $flat, Media $media)
    {
        $em = $this->getDoctrine()->getManager();

        $path = $media->getMediaName();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        $flat->removeMedia($media);
        $em->remove($media);

        $em->flush();

        return $this->redirectToRoute('admin_flat_edit', array('id' => $flat->getId()));
    }

    /**
     * @Route("/clone/{id}", name="admin_flat_clone")
     * @Method({"GET", "POST"})
     */
    public function cloneFlat(Request $request, Flat $flat)
    {
        $residence = $flat->getResidence();
        $cloneFlat = new Flat();
        $media = new Media();
        $cloneFlat->getMedias()->add($media);

        $form = $this->createForm(FlatType::class, $cloneFlat);

        //On charge les champs necessaire depuis l'appartement à cloner dans le formulaire du nouveau clone
        $form->get('reference')->setData($flat->getReference());
        $form->get('prix')->setData($flat->getPrix());
        $form->get('typeLogement')->setData($flat->getTypeLogement());
        $form->get('typeBien')->setData($flat->getTypeBien());
        $form->get('etage')->setData($flat->getEtage());
        $form->get('statut')->setData($flat->getStatut());
        $form->get('surface')->setData($flat->getSurface());
        $form->get('surfaceSejour')->setData($flat->getSurfaceSejour());
        $form->get('surfaceBalcon')->setData($flat->getSurfaceBalcon());
        $form->get('surfaceTerrasse')->setData($flat->getSurfaceTerrasse());
        $form->get('surfaceJardin')->setData($flat->getSurfaceJardin());
        $form->get('surfaceTerrain')->setData($flat->getSurfaceTerrain());
        $form->get('expositionSejour')->setData($flat->getExpositionSejour());
        $form->get('stationnement')->setData($flat->getStationnement());
        $form->get('dateLivraison')->setData($flat->getDateLivraison());
        $form->get('solSejour')->setData($flat->getSolSejour());
        $form->get('solSdb')->setData($flat->getSolSdb());
        $form->get('solChambre')->setData($flat->getSolChambre());
        $form->get('revetementMur')->setData($flat->getRevetementMur());
        $form->get('menuiserie')->setData($flat->getMenuiserie());
        $form->get('chauffage')->setData($flat->getChauffage());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $cloneFlat->setResidence($residence);

            // Si l'administrateur n'upload pas de photo pour le bien, une photo est chargée par défaut
            $medias = $cloneFlat->getMedias();
            foreach ($medias as $media) {
                if (is_null($media->getMediaName())) {
                    /* @var $media Media */
                    $media->setMediaName('default.jpg');
                    $date = new \DateTimeImmutable();
                    $media->setUpdatedAt($date);
                }
            }

            $em->persist($cloneFlat);
            $em->flush();

            return $this->redirectToRoute('admin_flat_index', array('id' => $cloneFlat->getResidence()->getId()));
        }

        return $this->render('flat/clone.html.twig', [
                'flat' => $cloneFlat,
                'residence' => $residence,
                'clone_form' => $form->createView()
            ]
        );
    }
}
