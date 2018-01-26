<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 12/06/17
 * Time: 14:57
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Quartier;
use MyOrleansBundle\Entity\Ville;
use MyOrleansBundle\Service\AutocompleteGenerator;
use MyOrleansBundle\Service\FormulaireContact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Repository\ArticleRepository;
use MyOrleansBundle\Service\MyOrleans_Twig_Extension;


class NosResidencesController extends Controller
{
    /**
     * @Route("/neuf", name="nosresidences")
     */
    public function nosResidencesAction(Request $request, FormulaireContact $formulaireContact)
    {

        // Definition des contenus associes par defaut
        $residencesSuggerees = '';
        $resultatRecherche = 0;

        // Generation du manager
        $em = $this->getDoctrine()->getManager();

        // Recuperation de la liste des villes et des quartiers dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();
        $quartiers = $em->getRepository(Quartier::class)->findAll();

        // Generation du moteur de recherche simplifie
        $simpleSearch = $this->createForm('MyOrleansBundle\Form\SimpleSearchType', null, ['action' => $this->generateUrl('nosresidences')]);
        $simpleSearch->handleRequest($request);

        // initialisation des variables ville et type a 0 si le formulaire simpleSearch n'est pas soumis
        $selectedVille = $selectedType = '';

        // affectation des valeurs ville et type si le form simpleSearch est valide
        if ($simpleSearch->isSubmitted() && $simpleSearch->isValid()) {

            // Prise en compte des filtres du moteur de recherche
            $data = $simpleSearch->getData();
            $selectedVille = $data['ville'];
            $selectedType = $data['type'];
            $residences = $em -> getRepository(Residence::class)->simpleSearch($selectedVille, $selectedType);

            if (!empty($residences)){
                $resultatRecherche = 2;
            }

            // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
            if(empty($residences)) {
                $residences = $em -> getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
                $resultatRecherche = 1;
            }

            // recherche des residences à exclure de la liste des residences à suggerer
            if (!empty($residences)) {
                foreach ($residences as $residence) {
                    $idResidences[] = $residence->getId();
                }
            }

            //récupération des résidences à suggerer
            if ($selectedVille != null || $selectedType != null) {
                $residencesSuggerees = $em->getRepository(Residence::class)->suggestResidence($idResidences);
            }

        }

        // Generation du moteur de recherche complet avec les valeurs ville et type definies ou non dans simpleSearch
        $completeSearch = $this->createForm('MyOrleansBundle\Form\CompleteSearchType', ['ville'=>$selectedVille, 'type'=>$selectedType], ['action' => $this->generateUrl('nosresidences-search')]);
        $completeSearch->handleRequest($request);

        // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
        if(empty($residences)) {
            $residences = $em -> getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
        }

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
            return $this->redirectToRoute('nosresidences');
        }

        return $this->render('MyOrleansBundle::nosResidences.html.twig', [
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
            'residencesSuggerees' => $residencesSuggerees,
            'residences' => $residences,
            'completeSearch' => $completeSearch->createView(),
            'villes' => $villes,
            'quartiers' => $quartiers,
            'resultatRecherche' => $resultatRecherche
        ]);

    }

    /**
     * @Route("/neuf/search", name="nosresidences-search")
     */
    public function completeSearchAction(Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        //Génération du moteur de recherche
        $completeSearch = $this->createForm('MyOrleansBundle\Form\CompleteSearchType');
        $completeSearch->handleRequest($request);

        // Recuperation de la liste des villes et des quartiers dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();
        $quartiers = $em->getRepository(Quartier::class)->findAll();

        // Traitement de la requete
        if ($completeSearch->isSubmitted()) {

            //Initialisation de la variable à 0 pour afficher la totalité des résidences
            $resultatRecherche = 0;

            //Récupération des résultats de recherche
            $data = $completeSearch->getData();

            //si aucun type de logement selectionné la variable est initialisé à null
            if ($data['typeLogement']->isEmpty()){
                $data['typeLogement'] = null;
            }

            //récupération des residences selon les choix pris en compte dans le moteur de recherche
            $residences = $em->getRepository(Residence::class)->completeSearch($data);

            //si des résidences sont trouveés, initialisation de la variable à 2 pur afficher des residences à suggerer
            if (!empty($residences)){
                $resultatRecherche = 2;
            }

            // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
            if(empty($residences)) {
                $residences = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
                $resultatRecherche = 1;
            }


            // recherche des residences à exclure de la liste des residences à suggerer
            if (!empty($residences)) {
                foreach ($residences as $residence) {
                    $idResidences[] = $residence->getId();
                }
            }

            // recherche des residences suggeres
            if ($data != null ) {
                $residencesSuggerees = $em -> getRepository(Residence::class)->suggestResidence($idResidences);
            }


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
                return $this->redirectToRoute('nosresidences');
        }


            return $this->render('MyOrleansBundle::nosResidences.html.twig',[
                'telephone_number' => $telephoneNumber,
                'form' => $formulaire->createView(),
                'completeSearch' => $completeSearch->createView(),
                'residencesSuggerees' => $residencesSuggerees,
                'residences' => $residences,
                'villes' => $villes,
                'quartiers' => $quartiers,
                'resultatRecherche' => $resultatRecherche
            ]);

        }

    }

}