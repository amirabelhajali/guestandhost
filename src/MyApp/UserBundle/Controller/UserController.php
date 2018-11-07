<?php

namespace MyApp\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    function testAction()
    {
        return $this->render('::base.html.twig');
    }

}
