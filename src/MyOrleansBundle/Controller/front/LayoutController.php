<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 12/09/2017
 * Time: 12:20
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Accueil;
use MyOrleansBundle\Entity\Article;
use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LayoutController extends Controller
{
    public function navBarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Recuperation de la liste des villes dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();

        // Fin recuperation des villes
        $simpleSearch = $this->createForm('MyOrleansBundle\Form\SimpleSearchType',
            null,
            ['action' => $this->generateUrl('nosresidences')]);

        return $this->render('MyOrleansBundle::navBar.html.twig', [
            'villes' => $villes,
            'simpleSearch' => $simpleSearch->createView(),
        ]);
    }

    public function formulaireAction(Request $request)
    {
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
            return $this->redirectToRoute('home');
        }

        return $this->render('MyOrleansBundle::formulaire.html.twig', [
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
        ]);
    }

    public function footerAction()
    {
        $em = $this->getDoctrine()->getManager();

        $programmes = $em->getRepository(Residence::class)->findAll();

        $services = $em->getRepository(Service::class)->findBy([], ['tri' => 'ASC']);

        $packs = $em->getRepository(Pack::class)->findBy([], ['tri' => 'ASC']);

        $articles = $em->getRepository(Article::class)->findBy([], ['date' => 'DESC'], 3, 0);

        $accueil = $em->getRepository(Accueil::class)->find(1);

        return $this->render('MyOrleansBundle::footer.html.twig', [
            'programmes' => $programmes,
            'services' => $services,
            'packs' => $packs,
            'articles' => $articles,
            'accueil' => $accueil
        ]);
    }
}