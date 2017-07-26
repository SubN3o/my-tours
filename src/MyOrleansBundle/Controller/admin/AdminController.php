<?php
/**
 * Created by PhpStorm.
 * User: HaGii
 * Date: 07/06/2017
 * Time: 10:15
 */

namespace MyOrleansBundle\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MyOrleansBundle\Repository\ResidenceRepository;
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

        $residences = $em->getRepository('MyOrleansBundle:Residence')->findAllLimit();

        return $this->render('index_admin.html.twig', array(
            'residences' => $residences,
        ));
    }
}