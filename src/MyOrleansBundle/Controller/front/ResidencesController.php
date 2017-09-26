<?php
/**
 * Created by PhpStorm.
 * User: wilder8
 * Date: 27/06/17
 * Time: 11:52
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypeLogement;
use MyOrleansBundle\Service\CalculateurCaracteristiquesResidence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ResidencesController extends Controller
{
    /**
     * @Route("/residences/{slug}", name="residences")
     * @ParamConverter("residence", class="MyOrleansBundle:Residence", options={"slug" = "slug"})
     */
    public function residenceAction(Residence $residence, SessionInterface $session, Request $request, CalculateurCaracteristiquesResidence $calculator)
    {
        $parcours = null;
        if ($session->has('parcours')) {
            $parcours = $session->get('parcours');
        }

        $em = $this->getDoctrine()->getManager();
        $flats = $em->getRepository(Flat::class)->findBy(['residence' => $residence], ['prix' => 'ASC']);
        $typelogment = $em->getRepository(TypeLogement::class)->findAll();
        $prixMin = $calculator->calculPrix($residence);
        $flatsDispo = $calculator->calculFlatDispo($residence);
        $typeMinMax = $calculator->calculSizes($residence);

        $medias = $residence->getMedias();
        $mediaDefine = [];
        foreach ($medias as $media) {
            if ($media->getTypeMedia()->getNom() == 'video') {
                $mediaDefine['video'] = $media;
            } elseif ($media->getTypeMedia()->getNom() == 'image-cover') {
                $mediaDefine['image-cover'] = $media;
            }
        }

        return $this->render('MyOrleansBundle::residence.html.twig', [
            'residence' => $residence,
            'flats' => $flats,
            'parcours' => $parcours,
            'media' => $mediaDefine,
            'prixMin' => $prixMin,
            'flatsDispo' => $flatsDispo,
            'typeMin' => $typeMinMax[0],
            'typeMax' => $typeMinMax[1],
            'typeLogement'=>$typelogment,
        ]);


    }

}