<?php
/**
 * Created by PhpStorm.
 * User: HaGii
 * Date: 12/06/2017
 * Time: 16:49
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Accueil;
use MyOrleansBundle\Entity\Chiffre;
use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Collaborateur;
use MyOrleansBundle\Entity\Evenement;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Partenaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AgenceController extends Controller
{
    /**
     * @Route("/my-orleans", name="my-orleans")
     */
    public function agencyAction(SessionInterface $session, Request $request)
    {
        //tableaux des mois en français
        $mois = [
            '01' => 'janvier',
            '02' => 'février',
            '03' => 'mars',
            '04' => 'avril',
            '05' => 'mai',
            '06' => 'juin',
            '07' => 'juillet',
            '08' => 'août',
            '09' => 'septembre',
            '10' => 'octobre',
            '11' => 'novembre',
            '12' => 'décembre',
        ];

        $em = $this->getDoctrine()->getManager();

        //récupération des partenaires
        $partenaires = $em->getRepository(Partenaire::class)->findBy([], ['tri'=>'ASC']);

        //récupération des collaborateurs dans la limite de 5 pour le barrillet
        //prévoir de modifier les angles dans le CSS si besoin de changer le nb de collaborateur
        $collaborateurs = $em->getRepository(Collaborateur::class)->findBy([], ['tri'=>'ASC'],5,0);

        //récupération des evenements et de leurs images
        $evenements = $em->getRepository(Evenement::class)->findBy([], ['dateDebut'=>'ASC']);
        $cover = $em->getRepository(Media::class)->findAll();

        //récupération de l'id1 de Accueil où sont stocké la video d'intro et le texte de présentation
        $accueil = $em->getRepository(Accueil::class)->find(1);

        //Formulaire de contact
        $client = new Client();
        $telephone_number = $this->getParameter('telephone_number');
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

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

            return $this->redirectToRoute('my-orleans');
        }

        return $this->render('MyOrleansBundle::my-orleans.html.twig',
            [
                'telephone_number' => $telephone_number,
                'mois' => $mois,
                'partenaires' => $partenaires,
                'collaborateurs' => $collaborateurs,
                'evenements' => $evenements,
                'cover' => $cover,
                'accueil' => $accueil,
                'form' => $formulaire->createView()

            ]
        );
    }
}