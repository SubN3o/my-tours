<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 08/09/2017
 * Time: 12:56
 */

namespace MyOrleansBundle\Controller\admin;


use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Service\CalculateurCaracteristiquesResidence;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ImageController extends Controller
{
    /**
     * Retrun a image file from a flat.
     * @return Response
     * @Route("/image/flat/{id}", name="flat_image")
     * @Method("GET")
     */
    public function imageFlatAction(Flat $flat, SessionInterface $session, Request $request, CalculateurCaracteristiquesResidence $calculateur)
    {
        $parcours = null;
        if ($session->has('parcours')) {
            $parcours = $session->get('parcours');
        }
        $em = $this->getDoctrine()->getManager();

        $residence = $flat->getResidence();
        $typelogement = $flat->getTypeLogement();
        $typebien = $flat->getTypeBien();
        $prixMin = $calculateur->calculPrix($residence);
        $flatsDispo = $calculateur->calculFlatDispo($residence);
        $typeMinMax = $calculateur->calculSizes($residence);
        $reference = $flat->getReference();
        $mailagence = $this->getParameter('mail_agence');
        $telephoneNumber = $this->getParameter('telephone_number');
//        $mappy = $this->get("knp_snappy.image");

        $medias = $flat->getMedias();
        $mediaDefine = [];
        foreach ($medias as $media) {
            if ($media->getTypeMedia()->getNom() == 'image') {
                $mediaDefine['image'] = $media;
            }elseif ($media->getTypeMedia()->getNom() == 'plan') {
                $mediaDefine['plan'] = $media;
            }
        }

        $html = $this->renderView('MyOrleansBundle::image_appartement.html.twig', array(
            'flat'=>$flat,
            'parcours'=>$parcours,
            'residence'=>$residence,
            'media' => $mediaDefine,
            'telephone_number' => $telephoneNumber,
            'typeBien'=>$typebien,
            'typeLogement' => $typelogement,
            'prixMin' => $prixMin,
            'flatsDispo' => $flatsDispo,
            'typeMin' => $typeMinMax[0],
            'typeMax' => $typeMinMax[1],
            'mail_agence'=>$mailagence,
            'reference' => $reference

        ));

        $filename = "appartement-".$flat->getReference().".jpg";

        return new Response(
//            $mappy->getOutputFromHtml($html),
            $this->get('knp_snappy.image')->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'image/jpg',
                'Content-Disposition' => 'filename="'.$filename.'"'
            ]);
    }
}