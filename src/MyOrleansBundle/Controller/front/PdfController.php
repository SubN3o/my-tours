<?php
/**
 * Created by PhpStorm.
 * User: wilder8
 * Date: 03/07/17
 * Time: 17:01
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypeBien;
use MyOrleansBundle\Entity\TypeLogement;
use MyOrleansBundle\Service\CalculateurCaracteristiquesResidence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PdfController extends Controller
{
    /**
     * Retrun a pdf file from a flat.
     * @return Response
     * @Route("/pdf/flat/{id}", name="flat_pdf")
     * @Method("GET")
     */
    public function pdfFlatAction(Flat $flat, SessionInterface $session, Request $request, CalculateurCaracteristiquesResidence $calculateur)
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
        $mappy = $this->get("knp_snappy.pdf");

        $medias = $flat->getMedias();
        $mediaDefine = [];
        foreach ($medias as $media) {
            if ($media->getTypeMedia()->getNom() == 'image') {
                $mediaDefine['image'] = $media;
            }elseif ($media->getTypeMedia()->getNom() == 'plan') {
                $mediaDefine['plan'] = $media;
            }
        }

        $html = $this->renderView('MyOrleansBundle::pdf_appartement.html.twig', array(
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

        $filename = "appartement-".$flat->getReference().".pdf";

        return new Response(
            $mappy->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]);

}
    /**
     * Retrun a pdf file from a residence.
     * @return Response
     * @Route("/pdf/residence/{id}", name="residence_pdf")
     * @Method("GET")
     */
    public function pdfResidenceAction(Residence $residence, SessionInterface $session, CalculateurCaracteristiquesResidence $calculateur)
    {
        $parcours = null;
        if ($session->has('parcours')) {
            $parcours = $session->get('parcours');
        }
        $em = $this->getDoctrine()->getManager();
        $flats = $em->getRepository(Flat::class)->findByResidence($residence);
        $typelogment = $em->getRepository(TypeLogement::class)->findAll();
        $typebien = $em->getRepository(TypeBien::class)->findAll();
        $prixMin = $calculateur->calculPrix($residence);
        $flatsDispo = $calculateur->calculFlatDispo($residence);
        $typeMinMax = $calculateur->calculSizes($residence);
        $mailagence = $this->getParameter('mail_agence');
        $googlemapstatickey = $this->getParameter('googlemap_static_map_key');
        $telephoneNumber = $this->getParameter('telephone_number');
        $mappy = $this->get("knp_snappy.pdf");

        $medias = $residence->getMedias();
        $mediaDefine = [];
        foreach ($medias as $media) {
            if ($media->getTypeMedia()->getNom() == 'image-cover') {
                $mediaDefine['image-cover'] = $media;
            }
        }

        $html = $this->renderView('MyOrleansBundle::pdf_residence.html.twig', array(
            'residence' => $residence,
            'flats' => $flats,
            'parcours' => $parcours,
            'media' => $mediaDefine,
            'telephone_number' => $telephoneNumber,
            'prixMin' => $prixMin,
            'flatsDispo' => $flatsDispo,
            'typeMin' => $typeMinMax[0],
            'typeMax' => $typeMinMax[1],
            'typeLogement'=>$typelogment,
            'typeBien'=>$typebien,
            'mail_agence'=>$mailagence,
            'googlemap_static_map_key'=>$googlemapstatickey,

        ));

        $filename = "residence-".$residence->getNom().".pdf";

        return new Response(
            $mappy->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]);

    }
}