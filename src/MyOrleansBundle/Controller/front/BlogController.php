<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 31/05/17
 * Time: 11:32
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Article;
use MyOrleansBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use MyOrleansBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BlogController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/blog", name="homeblog")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $telephoneNumber = $this->getParameter('telephone_number');
        $articles = $em->getRepository(Article::class)->findLatestArticles();

        // Formulaire de contact
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
//        $formulaire->get('sujet')->setData(Client::SUJET_INSCR_NEWSLETTER);
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
            return $this->redirectToRoute('homeblog');
        }

        return $this->render('MyOrleansBundle:blog:blog_home.html.twig', [
            'telephone_number' => $telephoneNumber,
            'articles' => $articles,
            'form' => $formulaire->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/blog/{slug}", name="blog-article")
     * @ParamConverter("article", class="MyOrleansBundle:Article", options={"slug" = "slug"})
     */
    public function afficherArticleAction(Request $request, Article $article)
    {
        $residence = $article->getResidence();
        $telephoneNumber = $this->getParameter('telephone_number');

        //Recuperation des tags de l'article et selection du premier tag
        $tags = $article->getTags();
        $tag = $tags[0]->getNom();

        //Fin recuperation du tag
        $em = $this->getDoctrine()->getManager();
        $articlesAssocies = $em->getRepository(Article::class)->articleByTag($tag, 2);

        // Formulaire de contact
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
//        $formulaire->get('sujet')->setData(Client::SUJET_INSCR_NEWSLETTER);
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
            return $this->redirectToRoute($this->generateUrl('blog-article', ['id' => $article->getId()]));
        }

        return $this->render('MyOrleansBundle:blog:blog_article.html.twig',[
            'article' => $article,
            'residence' => $residence,
            'telephone_number' => $telephoneNumber,
            'articlesAssocies' => $articlesAssocies,
            'form' => $formulaire->createView()
        ]);
    }

}