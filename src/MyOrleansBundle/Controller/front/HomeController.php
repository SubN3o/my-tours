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
    public function indexAction(SessionInterface $session, Request $request)
    {
        $parcours = null;
        if ($session->has('parcours')) {
            $parcours = $session->get('parcours');
        }
        $em = $this->getDoctrine()->getManager();

        $collaborateurs = $em->getRepository(Collaborateur::class)->findAll();

        $accueil = $em->getRepository(Accueil::class)->find(1);

        $residenceFav = $em->getRepository(Residence::class)->findOneFav();
        $residenceTwoFav = $em->getRepository(Residence::class)->findTwoFav();
        $residenceAll = $em->getRepository(Residence::class)->findAll();

        $testimonials = $em->getRepository(Temoignage::class)->findAll();

        $actu = $em->getRepository(Article::class)->findOneActu();
        $event = $em->getRepository(Evenement::class)->findOneEvent();

        $telephoneNumber = $this->getParameter('telephone_number');


        // Formulaire de contact
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->get('sujet')->setData(Client::SUJET_INSCR_NEWSLETTER);
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
            return $this->redirectToRoute('home');
        }


        // Recuperation de la liste des villes dans lesquelles se trouvent les residences
        $residences = $em->getRepository(Residence::class)->findAll();
        $villes = [];
        foreach ($residences as $residence) {
            $villes[] = $residence->getVille();
        }

        // Recuperation de la liste des villes dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();


        // Fin recuperation des villes
        $simpleSearch = $this->createForm('MyOrleansBundle\Form\SimpleSearchType',
            null,
            ['action' => $this->generateUrl('nosbiens')]);

                

        return $this->render('MyOrleansBundle::index.html.twig', [
            'parcours' => $parcours,
            'simpleSearch' => $simpleSearch->createView(),
            'villes' => $villes,
            'collaborateurs' => $collaborateurs,
            'residenceFav' => $residenceFav,
            'residenceTwoFav' => $residenceTwoFav,
            'residenceAll' => $residenceAll,
            'actu' => $actu,
            'event' => $event,
            'testimonials' => $testimonials,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
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
