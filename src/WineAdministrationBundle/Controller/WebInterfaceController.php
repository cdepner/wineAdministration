<?php

namespace WineAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
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
        $addParam = '';
        if ($searchCriteria) {
            $url = $this->generateUrl('wineadministration_api_showclient', array ('searchCriteria' => $addParam), true);
        } else {
            $url = $this->generateUrl('wineadministration_api_showclient', array(), true);
        }
        $users = json_decode($this->getApiRequest($url));

        return array(
            'users' => $users
        );
    }

    /**
     * Webinterface User editiern
     *
     * @param string  $searchCriteria Client ID oder Name -> Vor und Nachname werden mit _ getrennt
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/edit/client/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9_]+" },
     *       methods      = { "GET", "POST" }
     * )
     * @Template("WineAdministrationBundle:WebInterface:showClient.html.twig")
     *
     * @return array|Response
     */
    public function editClientAction($searchCriteria, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(array('Fehlerhafte Anfrage'));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $post = array();
        if ($searchCriteria && $request->getMethod() == 'POST') {
            if ($request->get('submit') && !$request->get('reset')) {
                $post['forename'] = $request->get('forename');
                $post['surname'] = $request->get('surname');
                $post['street'] = $request->get('street');
                $post['streetno'] = $request->get('streetno');
                $post['city'] = $request->get('city');
                $post['zipcode'] = $request->get('zipcode');
                $post['phone'] = $request->get('phone');
                $url = $this->generateUrl('wineadministration_api_editclient', array('searchCriteria' => $searchCriteria), true);
                $users = json_decode($this->postApiRequest($url, $post));
            } else if (!$request->get('submit') && $request->get('reset')) {
                $url = $this->generateUrl('wineadministration_api_deleteclient', array('searchCriteria' => $searchCriteria), true);
                $users = json_decode($this->getApiRequest($url, $post));
            }
            return array(
                'users' => $users
            );
        }

        return $response;
    }

    /**
     * Webinterface User hinzufügen
     *
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/add/client",
     *       methods      = { "GET", "POST" }
     * )
     * @Template("WineAdministrationBundle:WebInterface:showClient.html.twig")
     *
     * @return array|Response
     */
    public function addClientAction(Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $post = array();
        if ($request->getMethod() == 'POST') {
            $post['forename']  = $request->get('forename');
            $post['surname']   = $request->get('surname');
            $post['street']    = $request->get('street');
            $post['streetno']  = $request->get('streetno');
            $post['city']      = $request->get('city');
            $post['zipcode']   = $request->get('zipcode');
            $post['phone']     = $request->get('phone');
            $url = $this->generateUrl('wineadministration_api_addclient', array(), true);
            $users = json_decode($this->postApiRequest($url, $post));
            return array(
                'users' => $users
            );
        }

        return $response;
    }

    /**
     * Webinterface Wine anzeige
     *
     * @param string $searchCriteria Wine ID oder Name
     *
     * @Route(
     *       "/weinverwaltung/wine/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9]+" },
     *       methods      = { "GET" }
     * )
     * @Template()
     *
     * @return Response json
     */
    public function showWineAction($searchCriteria)
    {
        $addParam = '';
        if ($searchCriteria) {
            $addParam = '/'.$searchCriteria;
        }
        $url = 'http://wine.local/weinverwaltung/api/show/wine'.$addParam;

        $wines = json_decode($this->getApiRequest($url));

        return array(
            'wines' => $wines
        );
    }

    /**
     * Webinterface Wine editiern
     *
     * @param string  $searchCriteria Client ID oder Name -> Vor und Nachname werden mit _ getrennt
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/edit/client/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9]+" },
     *       methods      = { "GET", "POST" }
     * )
     * @Template("WineAdministrationBundle:WebInterface:showClient.html.twig")
     *
     * @return array|Response
     */
    public function editWineAction($searchCriteria, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $post = array();
        if ($request->getMethod() == 'POST') {
            if ($request->get('submit') && !$request->get('reset')) {
                $post['name']  = $request->get('name');
                $post['varietal']   = $request->get('varietal');
                $post['vintage']    = $request->get('vintage');
                $post['vineyard']  = $request->get('vineyard');
                $post['city']      = $request->get('city');
                $post['zipcode']   = $request->get('zipcode');
                $post['region']     = $request->get('region');
                $post['country']     = $request->get('country');
                $post['kind']     = $request->get('kind');
                $post['type']     = $request->get('type');
                $post['volume']     = $request->get('volume');
                $post['price']     = $request->get('price');
                $post['available']     = $request->get('available');
                $url = $this->generateUrl('wineadministration_api_editwine', array('searchCriteria' => $searchCriteria), true);
                $wines = json_decode($this->postApiRequest($url, $post));
            } else if (!$request->get('submit') && $request->get('reset')) {
                $url = $this->generateUrl('wineadministration_api_deletewine', array('searchCriteria' => $searchCriteria), true);
                $wines = json_decode($this->getApiRequest($url, $post));
            }

            return array(
                'users' => $wines
            );
        }

        return $response;
    }

    /**
     * Webinterface Wine hinzufügen
     *
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/add/client",
     *       methods      = { "GET", "POST" }
     * )
     * @Template("WineAdministrationBundle:WebInterface:showClient.html.twig")
     *
     * @return array|Response
     */
    public function addWineAction(Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $post = array();
        if ($request->getMethod() == 'POST') {
            $post['name']       = $request->get('name');
            $post['varietal']   = $request->get('varietal');
            $post['vintage']    = $request->get('vintage');
            $post['vineyard']   = $request->get('vineyard');
            $post['city']       = $request->get('city');
            $post['zipcode']    = $request->get('zipcode');
            $post['region']     = $request->get('region');
            $post['country']    = $request->get('country');
            $post['kind']       = $request->get('kind');
            $post['type']       = $request->get('type');
            $post['volume']     = $request->get('volume');
            $post['price']      = $request->get('price');
            $post['available']  = $request->get('available');
            $url = $this->generateUrl('wineadministration_api_addwine', array(), true);
            $wines = json_decode($this->postApiRequest($url, $post));
            return array(
                'users' => $wines
            );
        }

        return $response;
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

    /**
     * Funktion zum abfragen von JSON der API
     *
     * @param string $url Api URL
     *
     * @return string
     */
    private function getApiRequest($url)
    {
        $client = new Client();
        $request = $client->get($url, array());
        $response = $request->send()->getBody();

        return $response;
    }

    /**
     * Funktion zum abfragen von JSON der API
     *
     * @param string $url  Api URL
     * @param array  $post POST Daten
     *
     * @return string
     */
    private function postApiRequest($url, $post)
    {
        $client = new Client();
        $request = $client->post($url, array(), $post);
        $response = $request->send()->getBody();

        return $response;
    }
}
