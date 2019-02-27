<?php
/**
 * Created by PhpStorm.
 * User: HaGii
 * Date: 07/06/2017
 * Time: 10:15
 */

namespace MyToursBundle\Controller\admin;

use MyToursBundle\Entity\Flat;
use MyToursBundle\Entity\Residence;
use MyToursBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MyToursBundle\Repository\ResidenceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_home")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $residences = $em->getRepository('MyToursBundle:Residence')->findAllLimit();

        return $this->render('index_admin.html.twig', array(
            'residences' => $residences,
        ));
    }

    public function navBarAdminAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sumResidences = count($em->getRepository(Residence::class)->findAll());
        $sumFlats = count($em->getRepository(Flat::class)->findAll());
        $sumFlatsDispo = count($em->getRepository(Flat::class)->findByStatut(true));

        $profil = $this->getUser();

        return $this->render('compteur.html.twig', [
            'sumResidences' => $sumResidences,
            'sumFlats' => $sumFlats,
            'sumFlatsDispo' => $sumFlatsDispo,
        ]);
    }

    public function profilAdminAction()
    {
        $profil = $this->getUser();

        return $this->render('profil.html.twig', [
            'profil' => $profil
        ]);
    }
}