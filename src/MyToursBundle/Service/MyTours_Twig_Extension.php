<?php

namespace MyToursBundle\Service;

class MyTours_Twig_Extension
{

    public function getFunctions()
    {
        $twig = new \Twig_Environment();
        $twig->addExtension(new \Twig_Extensions_Extension_Text());
    }



}