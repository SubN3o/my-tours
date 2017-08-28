<?php
/**
 * Created by PhpStorm.
 * User: HaGii
 * Date: 12/06/2017
 * Time: 16:49
 */

namespace MyOrleansBundle\Controller\front;

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
     * @Route("/agence", name="agence")
     */
    public function agencyAction(SessionInterface $session, Request $request)
    {
        $client = new Client();

        $parcours = null;
        if ($session->has('parcours')) {
            $parcours = $session->get('parcours');
        }

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

        $chiffres = $em->getRepository(Chiffre::class)->findBy([], ['id'=>'ASC'], 3);


        $telephone_number = $this->getParameter('telephone_number');
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);

        $formulaire->get('sujet')->setData(Client::SUJET_AUTRES);

        $formulaire->handleRequest($request);

        $partenaires = $em->getRepository(Partenaire::class)->findAll();
        $collaborateurs = $em->getRepository(Collaborateur::class)->findAll();
        $evenements = $em->getRepository(Evenement::class)->findAll();
        $cover = $em->getRepository(Media::class)->findAll();

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

            return $this->redirectToRoute('agence');
        }

        return $this->render('MyOrleansBundle::agence.html.twig',
            [
                'telephone_number' => $telephone_number,
                'mois' => $mois,
                'parcours' => $parcours,
                'partenaires' => $partenaires,
                'collaborateurs' => $collaborateurs,
                'evenements' => $evenements,
                'cover' => $cover,
                'chiffres' => $chiffres,
                'form' => $formulaire->createView()

            ]
        );
    }
}