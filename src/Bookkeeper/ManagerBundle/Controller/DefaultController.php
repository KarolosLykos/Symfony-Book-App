<?php

namespace Bookkeeper\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BookkeeperManagerBundle:Default:index.html.twig');
    }
}
