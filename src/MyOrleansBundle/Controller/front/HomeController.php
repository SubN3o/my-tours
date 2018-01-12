<?php

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Accueil;
use MyOrleansBundle\Entity\Article;


use MyOrleansBundle\Entity\CategoriePresta;
use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Media;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Collaborateur;
use MyOrleansBundle\Entity\Evenement;
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\Temoignage;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypePresta;
use MyOrleansBundle\Entity\Ville;
use MyOrleansBundle\Form\SimpleSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        //récupération des collaborateurs dans la limite de 5 pour le barrillet
        //prévoir de modifier les angles dans le CSS si besoin de changer le nb de collaborateur
        $collaborateurs = $em->getRepository(Collaborateur::class)->findBy([], ['tri'=>'ASC'],5,0);

        //récupération de l'id1 de Accueil où sont stocké la video d'intro, les mentions légales et les honoraires
        $accueil = $em->getRepository(Accueil::class)->find(1);

        //récupération des résidences par colonne pour la mosaïque
        $residenceCol1 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],3,2);
        $residenceCol2 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,0);
        $residenceCol3 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,5);

        //récupération des temoignages
        $temoignages = $em->getRepository(Temoignage::class)->findAll();


        // Formulaire de contact

        $telephoneNumber = $this->getParameter('telephone_number');
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

            //Mail de confirmation
            $confirmation = new \Swift_Message('Confirmation de my-orleans.com');
            $confirmation
                ->setTo($client->getEmail())
                ->setFrom($this->getParameter('mailer_user'))
                ->setBody(
                    $this->renderView(
                        'MyOrleansBundle::confirmationForm.html.twig',
                        ['demande'=>$client->getMessage()]
                    ),
                    'text/html'
                );

            $mailer->send($confirmation);

            $mailer->send($message);

            $client->setDate(new \Datetime());

            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'votre message a bien été envoyé');
            return $this->redirectToRoute('home');
        }

        // Recuperation de la liste des villes dans lesquelles se trouvent les residences
//        $residences = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
//        $villes = [];
//        foreach ($residences as $residence) {
//            $villes[] = $residence->getVille();
//        }

        return $this->render('MyOrleansBundle::index.html.twig', [
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
            'collaborateurs' => $collaborateurs,
            'residenceCol1' => $residenceCol1,
            'residenceCol2' => $residenceCol2,
            'residenceCol3' => $residenceCol3,
            'temoignages' => $temoignages,
            'accueil' => $accueil

        ]);
    }


    /**
     * @Route("/admin")
     */
    public function admin()
    {
        return $this->render('MyOrleansBundle::admin.html.twig');
    }


}
