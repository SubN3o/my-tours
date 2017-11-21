<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 21/11/2017
 * Time: 14:54
 */

namespace MyOrleansBundle\Controller;


use MyOrleansBundle\Service\SiteMap;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteMapController extends Controller
{
    /**
     * Génère le sitemap du site.
     *
     * @Route("/sitemap.{_format}", name="front_sitemap", Requirements={"_format" = "xml"})
     */
    public function siteMapAction(SiteMap $sitemap)
    {
        return $this->render(
            'MyOrleansBundle::sitemap.xml.twig',
            ['urls' => $sitemap->generer()]
        );
    }
}