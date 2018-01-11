<?php
/**
 * Created by PhpStorm.
 * User: wilder8
 * Date: 03/07/17
 * Time: 17:01
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Location;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypeBien;
use MyOrleansBundle\Entity\TypeLogement;
use MyOrleansBundle\Service\CalculateurCaracteristiquesResidence;
use MyOrleansBundle\Service\CalculateurHonoraires;
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
     * @Route("/pdf-full/residence-{slug}/{reference}.pdf", name="flat_pdf_admin")
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
     * @Route("/pdf/residence-{slug}/{reference}.pdf", name="flat_pdf")
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

    /**
     * Retrun a pdf file from a location.
     * @return Response
     * @Route("/pdf/location/{reference}.pdf", name="location_pdf")
     * @ParamConverter("location", class="MyOrleansBundle:Location", options={"reference" = "reference"})
     * @Method("GET")
     */
    public function pdfLocationAction(Location $location, Request $request, CalculateurHonoraires $calculateurHonoraires)
    {

        $em = $this->getDoctrine()->getManager();

        //calcul des honoraires selon la surface
        $honoraires = $calculateurHonoraires->calculHonoraires($location->getSurface());

        $mailagence = $this->getParameter('mail_agence');
        $telephoneNumber = $this->getParameter('telephone_number');
        $snappy = $this->get("knp_snappy.pdf");
        $medias = $location->getMedias();


        $html = $this->renderView('MyOrleansBundle::pdf_location.html.twig', array(
            'base_dir' => $this->get('kernel')->getRootDir() . '/../web' . $request->getBasePath(),
            'location'=>$location,
            'honoraires'=>$honoraires,
            'medias' => $medias,
            'telephone_number' => $telephoneNumber,
            'mail_agence'=>$mailagence,

        ));

        $filename = $location->getTypeBien()->getNom()."-".$location->getReference().".pdf";

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]);

    }
}