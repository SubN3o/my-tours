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

        $em = $this->getDoctrine()->getManager();

        $flatsT1 = $em->getRepository(Flat::class)->flatByType($residence, 'T1');
        $flatsT2 = $em->getRepository(Flat::class)->flatByType($residence, 'T2');
        $flatsT3 = $em->getRepository(Flat::class)->flatByType($residence, 'T3');
        $flatsT4 = $em->getRepository(Flat::class)->flatByType($residence, 'T4');
        $flatsT5 = $em->getRepository(Flat::class)->flatByType($residence, 'T5+');

        $typelogment = $em->getRepository(TypeLogement::class)->findAll();
        $prixMin = $calculator->calculPrix($residence);
        $flatsDispo = $calculator->calculFlatDispo($residence);
        $typeMinMax = $calculator->calculSizes($residence);
        $medias = $residence->getMedias();

        $T1Dispo = count($em->getRepository(Flat::class)->flatDispoByType($residence, 'T1'));
        $T2Dispo = count($em->getRepository(Flat::class)->flatDispoByType($residence, 'T2'));
        $T3Dispo = count($em->getRepository(Flat::class)->flatDispoByType($residence, 'T3'));
        $T4Dispo = count($em->getRepository(Flat::class)->flatDispoByType($residence, 'T4'));
        $T5Dispo = count($em->getRepository(Flat::class)->flatDispoByType($residence, 'T5+'));


        $idResidence [] = $residence->getId();

        $residencesSuggerees = $em->getRepository(Residence::class)->suggestResidence($idResidence);


        $telephoneNumber = $this->getParameter('telephone_number');

        // Formulaire de contact
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $mailer = $this->get('mailer');

            $message = new \Swift_Message('Nouveau message de my-orleans.com');
            $message
                ->setTo($this->getParameter('mailer_user'))
                ->setFrom($this->getParameter('mailer_user'))
                ->setBody(
                    $this->renderView(

                        'MyOrleansBundle::receptionForm.html.twig',
                        array('client' => $client)
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'votre message a bien été envoyé');
            return $this->redirectToRoute('residences');
        }


        return $this->render('MyOrleansBundle::residence.html.twig', [
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
            'residence' => $residence,
            'flatsT1' => $flatsT1,
            'flatsT2' => $flatsT2,
            'flatsT3' => $flatsT3,
            'flatsT4' => $flatsT4,
            'flatsT5' => $flatsT5,
            'media' => $medias,
            'prixMin' => $prixMin,
            'flatsDispo' => $flatsDispo,
            'typeMin' => $typeMinMax[0],
            'typeMax' => $typeMinMax[1],
            'typeLogement' => $typelogment,
            'T1Dispo' => $T1Dispo,
            'T2Dispo' => $T2Dispo,
            'T3Dispo' => $T3Dispo,
            'T4Dispo' => $T4Dispo,
            'T5Dispo' => $T5Dispo,
            'residencesSuggerees' => $residencesSuggerees,

        ]);


    }

}