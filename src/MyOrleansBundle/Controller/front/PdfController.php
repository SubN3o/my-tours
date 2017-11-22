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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/pdf-full/{slug}/{reference}.pdf", name="flat_pdf_admin")
     * @ParamConverter("appartement", class="MyOrleansBundle:Flat", options={"reference" = "reference"})
     * @ParamConverter("residence", class="MyOrleansBundle:Residence", options={"slug" = "slug"})
     * @Method("GET")
     */
    public function pdfFlatAdminAction(Flat $flat, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $residence = $flat->getResidence();
        $mailagence = $this->getParameter('mail_agence');
        $telephoneNumber = $this->getParameter('telephone_number');
        $snappy = $this->get("knp_snappy.pdf");
        $medias = $residence->getMedias();


        $html = $this->renderView('MyOrleansBundle::pdf_appartement.html.twig', array(
            'base_dir' => $this->get('kernel')->getRootDir() . '/../web' . $request->getBasePath(),
            'flat'=>$flat,
            'residence'=>$residence,
            'medias' => $medias,
            'telephone_number' => $telephoneNumber,
            'mail_agence'=>$mailagence,

        ));

        $filename = $flat->getTypeBien()->getNom()."-".$flat->getReference().".pdf";

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]);

    }

    /**
     * Retrun a pdf file from a flat.
     * @return Response
     * @Route("/pdf/{slug}/{reference}.pdf", name="flat_pdf")
     * @ParamConverter("appartement", class="MyOrleansBundle:Flat", options={"reference" = "reference"})
     * @ParamConverter("residence", class="MyOrleansBundle:Residence", options={"slug" = "slug"})
     * @Method("GET")
     */
    public function pdfFlatAction(Flat $flat, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $residence = $flat->getResidence();
        $mailagence = $this->getParameter('mail_agence');
        $telephoneNumber = $this->getParameter('telephone_number');
        $snappy = $this->get("knp_snappy.pdf");
        $medias = $residence->getMedias();


        $html = $this->renderView('MyOrleansBundle::pdf_appartement.html.twig', array(
            'base_dir' => $this->get('kernel')->getRootDir() . '/../web' . $request->getBasePath(),
            'flat'=>$flat,
            'residence'=>$residence,
            'medias' => $medias,
            'telephone_number' => $telephoneNumber,
            'mail_agence'=>$mailagence,

        ));

        $filename = $flat->getTypeBien()->getNom()."-".$flat->getReference().".pdf";

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]);

    }

//    /**
//     * Retrun a pdf file from a residence.
//     * @return Response
//     * @Route("/pdf/residence/{id}", name="residence_pdf")
//     * @Method("GET")
//     */
//    public function pdfResidenceAction(Residence $residence, SessionInterface $session, CalculateurCaracteristiquesResidence $calculateur)
//    {
//        $parcours = null;
//        if ($session->has('parcours')) {
//            $parcours = $session->get('parcours');
//        }
//        $em = $this->getDoctrine()->getManager();
//        $flats = $em->getRepository(Flat::class)->findByResidence($residence);
//        $typelogment = $em->getRepository(TypeLogement::class)->findAll();
//        $typebien = $em->getRepository(TypeBien::class)->findAll();
//        $prixMin = $calculateur->calculPrix($residence);
//        $flatsDispo = $calculateur->calculFlatDispo($residence);
//        $typeMinMax = $calculateur->calculSizes($residence);
//        $mailagence = $this->getParameter('mail_agence');
//        $googlemapstatickey = $this->getParameter('googlemap_static_map_key');
//        $telephoneNumber = $this->getParameter('telephone_number');
//        $mappy = $this->get("knp_snappy.pdf");
//
//        $medias = $residence->getMedias();
//        $mediaDefine = [];
//        foreach ($medias as $media) {
//            if ($media->getTypeMedia()->getNom() == 'image-cover') {
//                $mediaDefine['image-cover'] = $media;
//            }
//        }
//
//        $html = $this->renderView('MyOrleansBundle::pdf_residence.html.twig', array(
//            'residence' => $residence,
//            'flats' => $flats,
//            'parcours' => $parcours,
//            'media' => $mediaDefine,
//            'telephone_number' => $telephoneNumber,
//            'prixMin' => $prixMin,
//            'flatsDispo' => $flatsDispo,
//            'typeMin' => $typeMinMax[0],
//            'typeMax' => $typeMinMax[1],
//            'typeLogement'=>$typelogment,
//            'typeBien'=>$typebien,
//            'mail_agence'=>$mailagence,
//            'googlemap_static_map_key'=>$googlemapstatickey,
//
//        ));
//
//        $filename = "residence-".$residence->getNom().".pdf";
//
//        return new Response(
//            $mappy->getOutputFromHtml($html),
//            200,
//            [
//                'Content-Type' => 'application/pdf',
//                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
//            ]);
//
//    }
}