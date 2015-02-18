<?php

namespace WineAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;

class WebInterfaceController extends Controller
{
    /**
     * Webinterface anzeige
     *
     * @Route(
     *       "/weinverwaltung",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9_]+" },
     *       methods      = { "GET" }
     * )
     * @Template()
     *
     * @return Response json
     */
    public function showWineAdministrationAction()
    {


        return array();
    }

    /**
     * Webinterface User anzeige
     *
     * @param string $searchCriteria Client ID oder Name -> Vor und Nachname werden mit _ getrennt
     *
     * @Route(
     *       "/weinverwaltung/client/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9_]+" },
     *       methods      = { "GET" }
     * )
     * @Template()
     *
     * @return Response json
     */
    public function showClientAction($searchCriteria)
    {
        $client = new Client();

        $request = $client->get($file, array());
        $request->setAuth($billigerData['user'], $billigerData['password']);
        $request->setResponseBody($target);
        try {
            $request->send();
        } catch (ClientErrorResponseException $e) {
            echo ' - FEHLER! - ' . $file . ' ist nicht vorhanden!';
        }

        return array();
    }

    /**
     * Webinterface Wine anzeige
     *
     * @param string $searchCriteria Wine ID oder Name
     *
     * @Route(
     *       "/weinverwaltung/wine/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9_]+" },
     *       methods      = { "GET" }
     * )
     * @Template()
     *
     * @return Response json
     */
    public function showWineAction($searchCriteria)
    {


        return array();
    }

    /**
     * Webinterface Order anzeige
     *
     * @param string $searchCriteria Order ID
     *
     * @Route(
     *       "/weinverwaltung/order/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9_]+" },
     *       methods      = { "GET" }
     * )
     * @Template()
     *
     * @return Response json
     */
    public function showOrderAction($searchCriteria)
    {


        return array();
    }
}
