<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 12/06/17
 * Time: 14:57
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Article;
use MyOrleansBundle\Entity\Quartier;
use MyOrleansBundle\Entity\Ville;
use MyOrleansBundle\Service\AutocompleteGenerator;
use MyOrleansBundle\Service\CalculateurCaracteristiquesResidence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Form\SimpleSearchType;
use MyOrleansBundle\Form\CompleteSearchType;
use MyOrleansBundle\Repository\ArticleRepository;
use MyOrleansBundle\Service\MyOrleans_Twig_Extension;


class NosBiensController extends Controller
{
    /**
     * @Route("/nos_biens", name="nosbiens")
     */
    public function nosBiensAction(Request $request, SessionInterface $session)
    {
        // Definition des contenus associes par defaut
        $message = "Découvrez les biens suggérés";
        $objectif = "investir";
        $suggestionActive = 0;
        $residencesSuggerees = '';
        $rechercheSansResultat = 0;

        // Definition du parcours du visiteur
        $parcours = null;

        if ($session->has('parcours')) {
            $parcours = $session->get('parcours');
            if ($parcours == $this->getParameter('parcours_investisseur')) {
                $objectif = 'investir';
            } else {
                $objectif = 'Residence Principale';
            }
        }

        // Generation du manager
        $em = $this->getDoctrine()->getManager();

        // Recuperation de la liste des villes et des quartiers dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();
        $quartiers = $em->getRepository(Quartier::class)->findAll();

        // Generation du dernier article avec le tag 'Investissement'
        $articles = $em->getRepository(Article::class)->articleByTag('Investissement', 1);
        if (!empty($articles)) {
            $article = $articles[0];
        }

        // Generation du moteur de recherche simplifie
        $simpleSearch = $this->createForm('MyOrleansBundle\Form\SimpleSearchType', null, ['action' => $this->generateUrl('nosbiens')]);
        $simpleSearch->handleRequest($request);

        // initialisation des variables ville et type a 0 si le formulaire simpleSearch n'est pas soumis
        $selectedVille = $selectedType = '';

        // affectation des valeurs ville et type si le form simpleSearch est valide
        if ($simpleSearch->isSubmitted() && $simpleSearch->isValid()) {

            // Envoi de contenu different en fonction du bouton clique : investisseur ou residence principale
            $objectif = 'investir';
            $tag = 'Investissement';
            $suggestionActive = 1;

            $session->set('parcours', $this->getParameter('parcours_investisseur'));

            if ($simpleSearch->get('resPrincipaleBtn')->isClicked()) {
                $objectif = $tag = 'Residence Principale';

                $session->set('parcours', $this->getParameter('parcours_residence'));

            }


            // Generation du dernier article avec le tag 'Residence Principale'
            $article = $em->getRepository(Article::class)->articleByTag($tag, 1);
            $article = $article[0];


            // Prise en compte des filtres du moteur de recherche
            $data = $simpleSearch->getData();
            $selectedVille = $data['ville'];
            $selectedType = $data['type'];
            $residences = $em -> getRepository(Residence::class)->simpleSearch($selectedVille, $selectedType);


            // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
            if(empty($residences)) {
                $residences = $em -> getRepository(Residence::class)->findAll();
                $rechercheSansResultat = 1;
            }

            // Parametrage du parcours visiteur
            $parcours = $session->get('parcours');

            // recherche des biens à exclure de la liste des biens à suggerer
            if (!empty($residences)) {
                foreach ($residences as $residence) {
                    $idResidences[] = $residence->getId();
                }
            }

            if ($selectedVille != null || $selectedType != null) {
                $residencesSuggerees = $em->getRepository(Residence::class)->simpleSuggestedSearch($idResidences, $selectedVille, $selectedType);
            }

        }

        // Generation du moteur de recherche complet avec les valeurs ville et type definies ou non dans simpleSearch
        $completeSearch = $this->createForm('MyOrleansBundle\Form\CompleteSearchType', ['ville'=>$selectedVille, 'type'=>$selectedType], ['action' => $this->generateUrl('nosbiens-search')]);
        $completeSearch->handleRequest($request);

        // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
        if(empty($residences)) {
            $residences = $em -> getRepository(Residence::class)->findAll();
        }

        return $this->render('MyOrleansBundle::nosbiens.html.twig', [
            'parcours' => $parcours,
            'suggestionActive' => $suggestionActive,
            'residencesSuggerees' => $residencesSuggerees,
            'residences' => $residences,
            'completeSearch' => $completeSearch->createView(),
            'completeSearchSmallScreen' => $completeSearch->createView(),
            'villes' => $villes,
            'quartiers' => $quartiers,
            'objectif' => $objectif,
            'article' => $article ?? null,
            'rechercheSansResultat' => $rechercheSansResultat
        ]);

    }

    /**
     * @Route("/nos-biens/search", name="nosbiens-search")
     */
    public function completeSearchAction(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();

        $completeSearch = $this->createForm('MyOrleansBundle\Form\CompleteSearchType');
        $completeSearch->handleRequest($request);

        // Recuperation de la liste des villes et des quartiers dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();
        $quartiers = $em->getRepository(Quartier::class)->findAll();

        // Traitement de la requete
        if ($completeSearch->isSubmitted()) {

            $suggestionActive = 1;
            $rechercheSansResultat = 0;
            $objectif = "";
            $article = [];

            $data = $completeSearch->getData();


            $residences = $em->getRepository(Residence::class)->completeSearch($data);

            // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
            if(empty($residences)) {
                $residences = $em->getRepository(Residence::class)->findAll();
                $rechercheSansResultat = 1;
            }


            // Fin contenu associe

            // recherche des biens à exclure de la liste des biens à suggerer
            if (!empty($residences)) {
                foreach ($residences as $residence) {
                    $idResidences[] = $residence->getId();
                }
            }

            // recherche des biens suggeres
            if ($data != null ) {
                $residencesSuggerees = $em -> getRepository(Residence::class)
                    ->completeSuggestedSearch($idResidences, $data);
            }

            // Generation des contenus associes en fonction de l'objectif
            if (isset($data['objectif']) && $data['objectif'] == 'Residence Principale') {

                // Generation du dernier article avec le tag 'Residence Principale'
                if ($article = $em->getRepository(Article::class)->articleByTag('Residence Principale', 1)) {
                    $article = $article[0];
                }

                $objectif = "Residence Principale";
                $session->set('parcours', $this->getParameter('parcours_residence'));
            }

            if (isset($data['objectif']) && $data['objectif'] == 'investir') {

                // Generation du derier article avec le tag 'Investissement'
                if ($article = $em->getRepository(Article::class)->articleByTag('Investissement', 1)) {
                    $article = $article[0];
                }

                $objectif = "investir";
                $session->set('parcours', $this->getParameter('parcours_investisseur'));
            }

            // Parametrage du parcours visiteur
            $parcours = $session->get('parcours');

            if (empty($objectif)) {
                $objectif = "investir";
            }

            return $this->render('MyOrleansBundle::nosbiens.html.twig',[
                'parcours' => $parcours,
                'completeSearch' => $completeSearch->createView(),
                'completeSearchSmallScreen' => $completeSearch->createView(),
                'suggestionActive' => $suggestionActive,
                'residencesSuggerees' => $residencesSuggerees,
                'residences' => $residences,
                'villes' => $villes,
                'quartiers' => $quartiers,
                'objectif' => $objectif,
                'article' => $article,
                'rechercheSansResultat' => $rechercheSansResultat
            ]);

        } else {
            return $this->redirectToRoute('nosbiens');
        }

    }

}