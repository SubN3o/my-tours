<?php
/**
 * Created by PhpStorm.
 * User: jean-baptiste
 * Date: 15/06/17
 * Time: 10:53
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/info-pratique", name="immo_pratique")
     */
    public function immoPratiqueAction(SessionInterface $session, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository(Article::class)->findBy([],['date'=>'DESC']);

        $articlesNoResult = 0;

        $articlesSearch = $this->createForm('MyOrleansBundle\Form\SearchArticleType', null, ['action' => $this->generateUrl('immo_pratique_resultat')]);

        $essentiel = $em->getRepository(Article::class)->findBy([],['tri'=>'ASC'],4);

        $telephoneNumber = $this->getParameter('telephone_number');

        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

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
            'articlesNoResult' => $articlesNoResult,
            'essentiel'=>$essentiel
        ]);
    }

    /**
     * @Route("/info-pratique/resultat", name="immo_pratique_resultat")
     */
    public function immoPratiqueResultatAction(SessionInterface $session, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $articlesNoResult = 0;

        $articlesSearch = $this->createForm('MyOrleansBundle\Form\SearchArticleType', null, ['action' => $this->generateUrl('immo_pratique_resultat')]);
        $articlesSearch->handleRequest($request);

        if ($articlesSearch->isSubmitted() && $articlesSearch->isValid()) {

            $data = $articlesSearch->getData();

            $articles = $em->getRepository(Article::class)->articleByKeyword($data);

            if ($articles == null){
                $articles = $em->getRepository(Article::class)->findBy([],['date'=>'DESC']);
                $articlesNoResult = 1;
            }
        }

        $essentiel = $em->getRepository(Article::class)->findBy([],['tri'=>'ASC'],4);

        $telephoneNumber = $this->getParameter('telephone_number');

        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

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
            'articlesNoResult' => $articlesNoResult,
            'essentiel'=>$essentiel
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/info-pratique/{slug}", name="article")
     * @ParamConverter("article", class="MyOrleansBundle:Article", options={"slug" = "slug"})
     */
    public function afficherArticleAction(Request $request, Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        $articleByType = '';

        //On récupère le type de l'article
        $type = $article->getTypeArticle()->getId();

        //On récupère l'Id de l'article
        $idArticle = $article->getId();

        //On récupère un tableau d'objet Article des autres articles du même Type
        $articlesByType = $em->getRepository(Article::class)->articleByType($idArticle, $type);

        //On créé un tableau d'Id des articles à exclurent
        $articleExclu = [$idArticle];

        if ($articlesByType != null) {
            //On créé un tableau avec les Id des autres articles
            $articlesId = [];

            foreach ($articlesByType as $articleId) {
                $articlesId[] = $articleId->getId();
            }

            //On compte le tableau d'Id
            $countArticlesId = count($articlesId);

            //On récupère un chiffre aléatoire entre 0 et le dernier index du tableau d'Id
            $random = rand(0, $countArticlesId - 1);

            //On récupère un Id tiré aléatoirement dans le tableau
            $idRandom = $articlesId[$random];

            $articleByType = $em->getRepository(Article::class)->find($idRandom);

            //On ajoute au tableau des articles à exclurent l'id Random
            $articleExclu = [$idArticle,$idRandom];
        }

        //On récupère le dernier article créé en excluant les articles précédents pour eviter une redondance
        $lastArticle = $em->getRepository(Article::class)->lastArticle($articleExclu);

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

        return $this->render('MyOrleansBundle::article.html.twig',[
            'article' => $article,
            'telephone_number' => $telephoneNumber,
            'articleByType' => $articleByType,
//            'random' => $random,
            'lastArticle'=>$lastArticle,
            'form' => $formulaire->createView()
        ]);
    }

}

