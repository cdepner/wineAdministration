<?php

namespace WineAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * Start Seite
     * 
     * @Route("/weinverwaltung/index.jsp")
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
