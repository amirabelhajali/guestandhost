<?php

namespace MyApp\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    function adminAction()
    {
        return $this->render(':default:BackEndIndex.html.twig');
    }

    function loginAction()
    {
        return $this->render(':admin:login.html.twig');
    }
}
