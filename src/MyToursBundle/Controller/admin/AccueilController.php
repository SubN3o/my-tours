<?php

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\Accueil;
use MyToursBundle\Entity\Media;
use MyToursBundle\Form\AccueilType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Accueil controller.
 *
 * @Route("/admin/accueil")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AccueilController extends Controller
{
    /**
     * Displays a form to edit an existing accueil entity.
     *
     * @Route("/{id}/edit", name="admin_accueil_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Accueil $accueil)
    {
        if ($accueil->getMedias()->isEmpty()) {
            $mediaIntro = new Media();
            $mediaHonoraires = new Media();
            $accueil->getMedias()->add($mediaIntro);
            $accueil->getMedias()->add($mediaHonoraires);
        }
        $editForm = $this->createForm(AccueilType::class, $accueil);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Vos infos générales ont bien été modifié');

            return $this->redirectToRoute('admin_accueil_edit', array('id' => $accueil->getId()));
        }

        return $this->render('accueil/edit.html.twig', array(
            'accueil' => $accueil,
            'edit_form' => $editForm->createView(),
        ));
    }
    
    /**
     * Deletes a accueil media.
     *
     * @Route("/{id}/delete_media/{media_id}", name="accueil_media_delete")
     * @ParamConverter("accueil", class="MyToursBundle:Accueil", options={"id" = "id"})
     * @ParamConverter("media", class="MyToursBundle:Media", options={"id" = "media_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Accueil $accueil, Media $media)
    {
        $em = $this->getDoctrine()->getManager();

        $path = $media->getMediaName();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        $accueil->removeMedia($media);
        $em->remove($media);

        $em->flush();
        return $this->redirectToRoute('admin_accueil_edit', array('id' => $accueil->getId()));
    }
}
