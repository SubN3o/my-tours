<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Accueil;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\TypeMedia;
use MyOrleansBundle\Form\AccueilType;
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

//            $em = $this->getDoctrine()->getManager();
//
//            // Si l'administrateur n'upload pas de photo pour l'accueil, une photo est chargée par défaut
//            $medias = $accueil->getMedias();
//            foreach ($medias as $media){
//                if (is_null($media->getMediaName())) {
//                    /* @var $media Media */
//                    $typeMediaImgCover = $em->getRepository(TypeMedia::class)->find(TypeMedia::IMAGE_COVER);
//                    $media->setTypeMedia($typeMediaImgCover);
//                    $media->setMediaName('default.jpg');
//                    $date = new \DateTimeImmutable();
//                    $media->setUpdatedAt($date);
//                }
//            }
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre accueil a bien été modifiée');

            return $this->redirectToRoute('admin_accueil_edit', array('id' => $accueil->getId()));
        }

        return $this->render('accueil/edit.html.twig', array(
            'accueil' => $accueil,
            'edit_form' => $editForm->createView(),
        ));
    }
}
