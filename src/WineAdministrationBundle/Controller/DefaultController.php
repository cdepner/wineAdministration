<?php
/**
 * Default Controller
 *
 * Default anfragen
 *
 * @author C. Depner
 */
namespace WineAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Default Controller
 *
 * Default anfragen
 */
class DefaultController extends Controller
{
    /**
     * Start Seite
     *
     * @Route("/weinverwaltung/index.jsp")
     * @Route("/weinverwaltung/index.php")
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('wineadministration_webinterface_showwineadministration'));
    }
}
