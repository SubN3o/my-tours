<?php
/**
 * Created by PhpStorm.
 * User: jean-baptiste
 * Date: 15/06/17
 * Time: 10:53
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyOrleansBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ImmoPratiqueController extends Controller
{

    /**
     * @Route("/infos-pratiques", name="immo_pratique")
     */
    public function immoPratiqueAction(FormulaireContact $formulaireContact, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // on récupere tous les articles trier par date en ordre décroissant
        $articles = $em->getRepository(Article::class)->findBy([],['date'=>'DESC']);

        // initialisation de la variable pour gérer le message de recherche
        $articlesNoResult = 0;

        // on redirige vers une autre url apres une recherche d'article
        $articlesSearch = $this->createForm('MyOrleansBundle\Form\SearchArticleType');

        $articlesSearch->handleRequest($request);

        if ($articlesSearch->isSubmitted() && $articlesSearch->isValid()) {

            // on récupere la donné de recherche
            $data = $articlesSearch->getData();

            // on récupère les articles qui contiennent le mot-clé
            $articles = $em->getRepository(Article::class)->articleByKeyword($data);

            //si aucun article n'est trouvé, on récupère la totalité des articles
            if ($articles == null){
                $articles = $em->getRepository(Article::class)->findBy([],['date'=>'DESC']);

                //on modifie la variable pour afficher un message
                $articlesNoResult = 1;
            }
        }

        // on récupere les 4 premiers article qui ont le champ tri de renseigné
        $essentiel = $em->getRepository(Article::class)->articleByTri();

        // Formulaire de contact

        //Récupération du téléphone de l'agence depuis les paramètres
        $telephoneNumber = $this->getParameter('telephone_number');

        //Instantacion d'un nouveau client
        $client = new Client();

        //Création du formulaire
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);

        //Récupération des infos rentrées du formulaire
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            //Utilisation du service FormulaireContact
            $formulaireContact->formulaireContact($client);

            //La date est setter à la création du client
            $client->setDate(new \Datetime());

            //Enregistrement du client dans la BDD
            $em->persist($client);
            $em->flush();

            //Création d'un message de réussite
            $this->addFlash('success', 'Votre message a bien été envoyé');

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
     * @Route("/infos-pratiques/{slug}", name="article")
     * @ParamConverter("article", class="MyOrleansBundle:Article", options={"slug" = "slug"})
     */
    public function afficherArticleAction(Request $request, Article $article, FormulaireContact $formulaireContact)
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

        // Formulaire de contact

        //Récupération du téléphone de l'agence depuis les paramètres
        $telephoneNumber = $this->getParameter('telephone_number');

        //Instantacion d'un nouveau client
        $client = new Client();

        //Création du formulaire
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);

        //Récupération des infos rentrées du formulaire
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            //Utilisation du service FormulaireContact
            $formulaireContact->formulaireContact($client);

            //La date est setter à la création du client
            $client->setDate(new \Datetime());

            //Enregistrement du client dans la BDD
            $em->persist($client);
            $em->flush();

            //Création d'un message de réussite
            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('article',['slug'=>$article->getSlug()]);
        }

        return $this->render('MyOrleansBundle::article.html.twig',[
            'article' => $article,
            'telephone_number' => $telephoneNumber,
            'articleByType' => $articleByType,
            'lastArticle'=>$lastArticle,
            'form' => $formulaire->createView()
        ]);
    }

}

