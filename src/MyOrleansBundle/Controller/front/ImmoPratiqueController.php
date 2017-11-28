<?php
/**
 * Created by PhpStorm.
 * User: jean-baptiste
 * Date: 15/06/17
 * Time: 10:53
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyOrleansBundle\Entity\Temoignage;
use MyOrleansBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ImmoPratiqueController extends Controller
{

    /**
     * @Route("/immo-pratique", name="immo_pratique")
     */
    public function immoPratiqueAction(SessionInterface $session, Request $request)
    {

        $telephoneNumber = $this->getParameter('telephone_number');

        $em = $this->getDoctrine()->getManager();
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        $articles = $em->getRepository(Article::class)->findAll();

        $articlesNoResult = 0;

        $articlesSearch = $this->createForm('MyOrleansBundle\Form\SearchArticleType', null, ['action' => $this->generateUrl('immo_pratique_resultat')]);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $mailer = $this->get('mailer');

            $message = new \Swift_Message('Nouveau message de my-orleans.com');
            $message
                ->setFrom($client->getEmail())
                ->setTo($this->getParameter('mailer_user'))


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

            return $this->redirectToRoute('immo_pratique');
        }

        return $this->render('MyOrleansBundle::immoPratique.html.twig', [
            'articles' => $articles,
            'telephone_number' =>$telephoneNumber,
            'form' => $formulaire->createView(),
            'articlesSearch' => $articlesSearch->createView(),
            'articlesNoResult' => $articlesNoResult
        ]);
    }

    /**
     * @Route("/immo-pratique/resultat", name="immo_pratique_resultat")
     */
    public function immoPratiqueResultatAction(SessionInterface $session, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $telephoneNumber = $this->getParameter('telephone_number');

        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        $articlesNoResult = 0;


        $articlesSearch = $this->createForm('MyOrleansBundle\Form\SearchArticleType', null, ['action' => $this->generateUrl('immo_pratique_resultat')]);
        $articlesSearch->handleRequest($request);

        if ($articlesSearch->isSubmitted() && $articlesSearch->isValid()) {

            $data = $articlesSearch->getData();

            $articles = $em->getRepository(Article::class)->articleByTag($data);

            if ($articles == null){
                $articles = $em->getRepository(Article::class)->findAll();
                $articlesNoResult = 1;
            }
        }

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $mailer = $this->get('mailer');

            $message = new \Swift_Message('Nouveau message de my-orleans.com');
            $message
                ->setFrom($client->getEmail())
                ->setTo($this->getParameter('mailer_user'))


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

            return $this->redirectToRoute('immo_pratique');
        }

        return $this->render('MyOrleansBundle::immoPratique.html.twig', [
            'articles' => $articles,
            'telephone_number' =>$telephoneNumber,
            'form' => $formulaire->createView(),
            'articlesSearch' => $articlesSearch->createView(),
            'articlesNoResult' => $articlesNoResult
        ]);
    }

}

