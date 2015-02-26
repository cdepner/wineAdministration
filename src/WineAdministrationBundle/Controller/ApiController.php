<?php
/**
 * API Controller
 *
 * Verarbeitung, auswertung und ausgabe aller API anfragen
 *
 * @author C. Depner
 */
namespace WineAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WineAdministrationBundle\Entity\City;
use WineAdministrationBundle\Entity\Client;
use WineAdministrationBundle\Entity\ClientorderRepository;
use WineAdministrationBundle\Entity\Clientorder;
use WineAdministrationBundle\Entity\ClientRepository;
use WineAdministrationBundle\Entity\Clientphone;
use WineAdministrationBundle\Entity\Country;
use WineAdministrationBundle\Entity\Region;
use WineAdministrationBundle\Entity\Vineyard;
use WineAdministrationBundle\Entity\Wine;
use WineAdministrationBundle\Entity\WineRepository;
use WineAdministrationBundle\Entity\Winekind;
use WineAdministrationBundle\Entity\Winetoclientorder;
use WineAdministrationBundle\Entity\WinetoclientorderRepository;
use WineAdministrationBundle\Entity\Winetowinevarietal;
use WineAdministrationBundle\Entity\Winetype;
use WineAdministrationBundle\Entity\Winevarietal;

/**
 * API Controller
 *
 * Verarbeitung, auswertung und ausgabe aller API anfragen
 */
class ApiController extends Controller
{


    /**
     * Api Client hinzufügen
     *
     * @param Request $request array(
     *      forename    => String,
     *      surname     => String,
     *      street      => String,
     *      streetno    => String,
     *      city        => String,
     *      zipcode     => Int(5),
     *      phone       => String,String,...
     * )
     *
     * @Route(
     *       "/weinverwaltung/api/add/client",
     *       methods = { "POST", "PUT" }
     * )
     *
     * @return Response json
     */
    public function addClientAction(Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $newClient = $this->validationClientAddPost($request);
        if (!$newClient['failure']) {
            //Kunde in die Datenbank schreiben
            $client = $this->addClient($newClient);
            if ($client != null) {
                //Kunde für JSON Ausgabe vorbereiten
                $usersArray = $this->getClientArray($client);
                if (!empty($usersArray)) {
                    $response = new Response(json_encode($usersArray));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_CREATED);
                }
            }
        } else {
            //Ausgabe für Fehlerhaften Post
            $response = new Response(json_encode($newClient));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    /**
     * Api Wein erstellen
     *
     * @param Request $request array(
     *      available   => Bool,
     *      name        => String,
     *      vineyard    => String,
     *      city        => String,
     *      zipcode     => Int(5),
     *      region      => String,
     *      country     => String,
     *      kind        => String,
     *      type        => String,
     *      vintage     => Int(4),
     *      volume      => Float,
     *      price       => Float,
     *      varietal => array(
     *          String,
     *          String,
     *          ...
     *      )
     * )
     *
     * @Route(
     *       "/weinverwaltung/api/add/wine",
     *       methods = { "POST", "PUT" }
     * )
     *
     * @return Response json
     */
    public function addWineAction(Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $newWine = $this->validationWineAddPost($request);
        if (!$newWine['failure']) {
            //Wein in die Datenbank schreiben
            $wine = $this->addWine($newWine);
            if ($wine != null) {
                //Wein für JSON Ausgabe vorbereiten
                $wineArray = $this->getWineArray($wine);
                if (!empty($wineArray)) {
                    $response = new Response(json_encode($wineArray));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_CREATED);
                }
            }
        } else {
            //Ausgabe für Fehlerhaften Post
            $response = new Response(json_encode($newWine));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    /**
     * Api Bestellung erstellen
     *
     * @param Request $request array(
     *      client  => Int,
     *      date    => String,
     *      wine    => array(
     *          amount  => Int,
     *          price   => Float,
     *          wine    => Int
     *      )
     * )
     *
     * @Route(
     *       "/weinverwaltung/api/add/order",
     *       methods = { "GET", "POST", "PUT" }
     * )
     *
     * @return Response json
     */
    public function addOrderAction(Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $newOrder = $this->validationOrderAddPost($request);
        if (!$newOrder['failure']) {
            //Wein in die Datenbank schreiben
            $order = $this->addOrder($newOrder);
            if ($order != null) {
                //Wein für JSON Ausgabe vorbereiten
                $orderArray = $this->getOrderArray($order);
                if (!empty($orderArray)) {
                    $response = new Response(json_encode($orderArray));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_CREATED);
                }
            }
        } else {
            //Ausgabe für Fehlerhaften Post
            $response = new Response(json_encode($newOrder));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    /**
     * Funktion zum Validieren eines POST für einen Kunden
     *
     * @param Request $request
     *
     * @return array
     */
    private function validationClientAddPost(Request $request)
    {
        $newClient['failure'] = false;
        $newClient['forename']  = $request->get('forename') != null    ? $request->get('forename')            : !$newClient['failure'] = true;
        $newClient['surname']   = $request->get('surname') != null     ? $request->get('surname')             : !$newClient['failure'] = true;
        $newClient['street']    = $request->get('street') != null      ? $request->get('street')              : !$newClient['failure'] = true;
        $newClient['streetno']  = $request->get('streetno') != null    ? $request->get('streetno')            : !$newClient['failure'] = true;
        $newClient['city']      = $request->get('city') != null        ? $request->get('city')                : !$newClient['failure'] = true;
        $newClient['phone']     = $request->get('phone') != null       ? explode(',', $request->get('phone')) : !$newClient['failure'] = true;
        if ($request->get('zipcode') != null && is_int((int)$request->get('zipcode'))) {
            $newClient['zipcode'] = $request->get('zipcode');
        } else {
            $newClient['zipcode'] = false;
            $newClient['failure'] = true;
        }

        return $newClient;
    }

    /**
     * Funktion zum Validieren eines POST für einen Weines
     *
     * @param Request $request
     *
     * @return array
     */
    private function validationWineAddPost(Request $request)
    {
        $newWine['failure'] = false;
        //Post überprüfung
        $newWine['name']        = $request->get('name') != null        ? $request->get('name')                    : !$newWine['failure'] = true;
        $newWine['vineyard']    = $request->get('vineyard') != null    ? $request->get('vineyard')                : !$newWine['failure'] = true;
        $newWine['city']        = $request->get('city') != null        ? $request->get('city')                    : !$newWine['failure'] = true;
        $newWine['region']      = $request->get('region') != null      ? $request->get('type')                    : !$newWine['failure'] = true;
        $newWine['country']     = $request->get('country') != null     ? $request->get('type')                    : !$newWine['failure'] = true;
        $newWine['kind']        = $request->get('kind') != null        ? $request->get('kind')                    : !$newWine['failure'] = true;
        $newWine['type']        = $request->get('type') != null        ? $request->get('type')                    : !$newWine['failure'] = true;
        $newWine['varietal']    = $request->get('varietal') != null    ? explode(',', $request->get('varietal'))  : !$newWine['failure'] = true;
        if ($request->get('available') != null && is_bool((boolean)$request->get('available'))) {
            $newWine['available'] = (boolean)$request->get('available');
        } else {
            $newWine['available'] = false;
            $newWine['failure'] = true;
        }
        if ($request->get('vintage') != null && strlen($request->get('vintage')) == 4 && is_int((int)$request->get('vintage'))) {
            $newWine['vintage'] = \DateTime::createFromFormat("Y", (int)$request->get('vintage'));
        } else {
            $newWine['vintage'] = false;
            $newWine['failure'] = true;
        }
        if ($request->get('price') != null && is_float((float)$request->get('price'))) {
            $newWine['price'] = $request->get('price');
        } else {
            $newWine['price'] = false;
            $newWine['failure'] = true;
        }
        if ($request->get('volume') != null && is_float((float)$request->get('volume'))) {
            $newWine['volume'] = $request->get('volume');
        } else {
            $newWine['volume'] = false;
            $newWine['failure'] = true;
        }
        if ($request->get('zipcode') != null && is_int((int)$request->get('zipcode'))) {
            $newWine['zipcode'] = $request->get('zipcode');
        } else {
            $newWine['zipcode'] = false;
            $newWine['failure'] = true;
        }

        return $newWine;
    }

    /**
     * Funktion zum Validieren eines POST für einer Bestellung
     *
     * @param Request $request
     *
     * @return array
     */
    private function validationOrderAddPost(Request $request)
    {
        $newOrder['failure'] = false;
        //Post überprüfung
        if ($request->get('wine') != null && is_array($request->get('wine'))) {
            $orders = $request->get('wine');
            $newOrder['wine'] = array();
            foreach ($orders as $order) {
                if (!empty($order['id'])) {
                    $wine['id'] = $order['id'];
                } else {
                    $wine['id'] = false;
                    $newOrder['failure'] = true;
                }
                if (!empty($order['amount'])) {
                    $wine['amount'] = $order['amount'];
                } else {
                    $wine['amount'] = false;
                    $newOrder['failure'] = true;
                }
                if (!empty($order['price'])) {
                    $wine['price'] = $order['price'];
                } else {
                    $wine['price'] = false;
                    $newOrder['failure'] = true;
                }
                $newOrder['wine'][] = $wine;
            }
        } else {
            $newOrder['wine'] = false;
            $newOrder['failure'] = true;
        }
        if ($request->get('client') != null && is_int((int)$request->get('client'))) {
            $newOrder['client'] = $request->get('client');
        } else {
            $newOrder['client'] = false;
            $newOrder['failure'] = true;
        }
        if ($request->get('date') != null && \DateTime::createFromFormat('d.m.Y',$request->get('date'))) {
            $newOrder['date'] = \DateTime::createFromFormat("d.m.Y", $request->get('date'));
        } else {
            $newOrder['date'] = false;
            $newOrder['failure'] = true;
        }

        return $newOrder;
    }

    /**
     * Funktion zum hinzufügen von Daten in die Datenbank
     *
     * @param array $clientArray
     *
     * @return Client
     */
    private function addClient(array $clientArray)
    {
        $em = $this->getDoctrine()->getManager();
        $cityRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:City');
        $city = $cityRepo->findOneBy(array('name' => $clientArray['city'], 'zipcode' => $clientArray['zipcode']));
        if (!$city) {
            $city = new City($clientArray['city'], $clientArray['zipcode']);
            $em->persist($city);
            $em->flush();
        }
        if ($city) {
            $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
            $client = $clientRepo->findOneBy(array(
                'forename'  => $clientArray['forename'],
                'surname'   => $clientArray['surname'],
                'street'    => $clientArray['street'],
                'streetno'  => $clientArray['streetno'],
                'city'      => $city
            ));
            if (!$client) {
                $client = new Client($clientArray['forename'], $clientArray['surname'], $clientArray['street'], $clientArray['streetno'], $city);
                $em->persist($client);
                $em->flush();
            }
            if ($client) {
                $clientphoneRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientphone');
                foreach ($clientArray['phone'] as $phone) {
                    $clientphone = $clientphoneRepo->findOneBy(array('number' => $phone));
                    if (!$clientphone) {
                        $clientphone = new Clientphone($phone, $client);
                        $em->persist($clientphone);
                        $em->flush();
                    }
                }

                return $client;
            }
        }

        return null;
    }

    /**
     * Funktion zum hinzufügen von Daten in die Datenbank
     *
     * @param array $wineArray
     *
     * @return Wine
     */
    private function addWine(array $wineArray)
    {
        $em = $this->getDoctrine()->getManager();
        $cityRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:City');
        $city = $cityRepo->findOneBy(array('name' => $wineArray['city'], 'zipcode' => $wineArray['zipcode']));
        if (!$city) {
            $city = new City($wineArray['city'], $wineArray['zipcode']);
            $em->persist($city);
            $em->flush();
        }
        $countryRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Country');
        $country = $countryRepo->findOneBy(array('name' => $wineArray['country']));
        if (!$country) {
            $country = new Country($wineArray['country']);
            $em->persist($country);
            $em->flush();
        }
        if ($country) {
            $regionRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Region');
            $region = $regionRepo->findOneBy(array('name' => $wineArray['region'], 'country' => $country));
            if (!$region) {
                $region = new Region($wineArray['region'], $country);
                $em->persist($region);
                $em->flush();
            }
            if ($city && $region) {
                $vineyardRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Vineyard');
                $vineyard = $vineyardRepo->findOneBy(array('name' => $wineArray['vineyard'], 'region' => $region, 'city' => $city));
                if (!$vineyard) {
                    $vineyard = new Vineyard($wineArray['vineyard'], $city, $region);
                    $em->persist($vineyard);
                    $em->flush();
                }
                $winekindRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winekind');
                $winekind = $winekindRepo->findOneBy(array('name' => $wineArray['kind']));
                if (!$winekind) {
                    $winekind = new Winekind($wineArray['name']);
                    $em->persist($winekind);
                    $em->flush();
                }
                $winetypeRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetype');
                $winetype = $winetypeRepo->findOneBy(array('name' => $wineArray['type']));
                if (!$winetype) {
                    $winetype = new Winetype($wineArray['name']);
                    $em->persist($winetype);
                    $em->flush();
                }
                if ($vineyard && $winetype && $winekind) {
                    $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
                    $wine = $clientRepo->findOneBy(array(
                        'name'      => $wineArray['name'],
                        'vintage'   => $wineArray['vintage'],
                        'volume'    => $wineArray['volume'],
                        'winekind'  => $winekind,
                        'winetype'  => $winetype,
                        'vineyard'   => $vineyard
                    ));
                    if (!$wine) {
                        $wine = new Wine($wineArray['available'], $wineArray['price'], $wineArray['name'], $wineArray['vintage'], $wineArray['volume'], $vineyard, $winekind, $winetype);
                        $em->persist($wine);
                        $em->flush();
                    }
                    if ($wine) {
                        $winevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winevarietal');
                        $winetowinevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetowinevarietal');
                        foreach ($wineArray['varietal'] as $varietal) {
                            $winevarietal = $winevarietalRepo->findOneBy(array('name' => $varietal));
                            if (!$winevarietal) {
                                $winevarietal = new Winevarietal($varietal);
                                $em->persist($winevarietal);
                                $em->flush();
                            }
                            $winetowinevarietal = $winetowinevarietalRepo->findOneBy(array('winevarietal' => $winevarietal, 'wine' => $wine));
                            if (!$winetowinevarietal) {
                                $winetowinevarietal = new Winetowinevarietal($wine, $winevarietal);
                                $em->persist($winetowinevarietal);
                                $em->flush();
                            }
                        }

                        return $wine;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Funktion zum hinzufügen von Daten in die Datenbank
     *
     * @param array $orderArray
     *
     * @return array
     */
    private function addOrder(array $orderArray)
    {
        $em = $this->getDoctrine()->getManager();
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        $client = $clientRepo->findOneBy(array('id' => $orderArray['client']));
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        $wineArray = array();
        $wineFound = true;
        foreach ($orderArray['wine'] as $wine) {
            if (!$wineRepo->findOneBy(array('id' => $wine['id']))) {
                $wineFound = false;
            }
            $tmpWine['wine']    = $wineRepo->findOneBy(array('id' => $wine['id']));
            $tmpWine['amount']  = $wine['amount'];
            $tmpWine['price']   = $wine['price'];
            $wineArray[]        = $tmpWine;
        }
        if ($wineFound && $client) {
            $clientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientorder');
            $clientorder = $clientorderRepo->findOneBy(array('client' => $client, 'orderdate' => $orderArray['date']));
            if (!$clientorder) {
                $clientorder = new Clientorder($orderArray['date'], $client);
                $em->persist($clientorder);
                $em->flush();
            }
            if ($clientorder) {
                $wineObj = array();
                foreach ($wineArray as $wineorder) {
                    $winetoclientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
                    $winetoclientorder = $winetoclientorderRepo->findOneBy(array('clientOrder' => $clientorder, 'wine' => $wineorder['wine']));
                    if (!$winetoclientorder) {
                        $winetoclientorder = new Winetoclientorder((int)$wineorder['amount'], (float)$wineorder['price'], $clientorder, $wineorder['wine']);
                        $em->persist($winetoclientorder);
                        $em->flush();
                    }
                    $wineObj[] = $winetoclientorder;
                }

                return $wineObj;
            }
        }

        return null;
    }

    /**
     * Api User anfrage
     *
     * @param string $searchCriteria Client ID oder Name -> Vor und Nachname werden mit _ getrennt
     *
     * @Route(
     *       "/weinverwaltung/api/show/client/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9_]+" },
     *       methods      = { "GET" }
     * )
     *
     * @return Response json
     */
    public function showClientAction($searchCriteria)
    {
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $clients = $clientRepo->findBy(array('id' => $searchCriteria));
        } elseif ($searchCriteria != null && strpos($searchCriteria, '_')) {
            $criteria = explode('_', $searchCriteria);
            $clients = $clientRepo->getClientByFullName($criteria);
        } elseif ($searchCriteria != null) {
            $criteria = explode('_', $searchCriteria);
            $clients = $clientRepo->getClientByName($criteria);
        } else {
            $clients = $clientRepo->findAll();
        }
        $usersArray = $this->getClientArray($clients);
        if (empty($usersArray)) {
            $response = new Response(json_encode(array('error' => 'Fehlerhafte Kunden ID')));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        } else {
            $response = new Response(json_encode($usersArray));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);
        }

        return $response;
    }

    /**
     * Api Wein anfrage
     *
     * @param string $searchCriteria Wein ID oder Name
     *
     * @Route(
     *       "/weinverwaltung/api/show/wine/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9]+" },
     *       methods      = { "GET" }
     * )
     *
     * @return Response json
     */
    public function showWineAction($searchCriteria)
    {
        /** @var WineRepository $wineRepo */
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $wines = $wineRepo->findBy(array('id' => $searchCriteria));
        } elseif ($searchCriteria != null) {
            $wines = $wineRepo->getWineByName($searchCriteria);
        } else {
            $wines = $wineRepo->findAll();
        }
        $winesArray = $this->getWineArray($wines);
        if (empty($winesArray)) {
            $response = new Response(json_encode(array('error' => 'Fehlerhafte Wein ID')));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        } else {
            $response = new Response(json_encode($winesArray));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);
        }

        return $response;
    }


    /**
     * Api Bestell Anfrage
     *
     * @param string $searchCriteria Client ID
     *
     * @Route(
     *       "/weinverwaltung/api/show/order/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[a-zA-Z0-9]+" },
     *       methods      = { "GET" }
     * )
     *
     * @return Response json
     */
    public function showOrderAction($searchCriteria)
    {
        /** @var ClientorderRepository $clientorderRepository */
        $clientorderRepository = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientorder');
        /** @var WinetoclientorderRepository $winetoclientorderRepository */
        $winetoclientorderRepository = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $clientorder = $clientorderRepository->findBy(array('id' => $searchCriteria));
            $orders = $winetoclientorderRepository->findBy(array('clientOrder' => $clientorder));
        } elseif ($searchCriteria != null) {
            $clientorder = $clientorderRepository->getOrderByName($searchCriteria);
            $orders = $winetoclientorderRepository->findBy(array('clientOrder' => $clientorder));
        } else {
            $clientorders = $clientorderRepository->findAll();
            $orders = array();
            foreach ($clientorders as $clientorder) {
                $orders = array_merge($orders, $winetoclientorderRepository->findBy(array('clientOrder' => $clientorder)));
            }
        }
        $orderSorted = $this->getOrderArray($orders);
        $jsonOrder = json_encode($orderSorted);
        $response = new Response($jsonOrder);
        $response->headers->set('Content-Type', 'application/json');
        if (empty($orderSorted)) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        } else {
            $response->setStatusCode(Response::HTTP_OK);
        }

        return $response;
    }

    /**
     * Erzeug ein Wein Array
     *
     * @param array|Wine $winesObj
     *
     * @return array
     */
    private function getWineArray($winesObj)
    {
        $winesArray = array();
        if ($winesObj) {
            if (!is_array($winesObj)) {
                $wines[0] = $winesObj;
            } else {
                $wines = $winesObj;
            }
            foreach ($wines as $wine) {
                /** @var Wine $wine */
                $winesArray[] = array(
                    'id'        => $wine->getId(),
                    'available' => $wine->getAvailable(),
                    'price'     => $wine->getPrice(),
                    'name'      => $wine->getName(),
                    'vintage'   => $wine->getVintage()->format('Y'),
                    'volume'    => $wine->getVolume(),
                    'vineyard'  => $wine->getVineyard()->getName(),
                    'city'      => $wine->getVineyard()->getCity()->getName(),
                    'zipcode'   => $wine->getVineyard()->getCity()->getZipcode(),
                    'region'    => $wine->getVineyard()->getRegion()->getName(),
                    'country'   => $wine->getVineyard()->getRegion()->getCountry()->getName(),
                    'kind'      => $wine->getWinekind()->getName(),
                    'type'      => $wine->getWinetype()->getName(),
                    'varietal'  => $this->getWineVarietal($wine)
                );
            }
        }

        return $winesArray;
    }

    /**
     * Erzeug ein Kunden Array
     *
     * @param array|Client $clientsObj
     *
     * @return array
     */
    private function getClientArray($clientsObj)
    {
        $usersArray = array();
        if ($clientsObj) {
            if (!is_array($clientsObj)) {
                $clients[0] = $clientsObj;
            } else {
                $clients = $clientsObj;
            }
            foreach ($clients as $client) {
                /** @var Client $client */
                $usersArray[] = array(
                    'id'        => $client->getId(),
                    'forename'  => $client->getForename(),
                    'surname'   => $client->getSurname(),
                    'street'    => $client->getStreet(),
                    'streetNo'  => $client->getStreetno(),
                    'city'      => $client->getCity()->getName(),
                    'zipCode'   => $client->getCity()->getZipcode(),
                    'phone'     => $this->getClientPhoneNumbers($client)
                );
            }
        }

        return $usersArray;
    }

    /**
     * Erzeugt ein Kundenbestellungs Array
     *
     * @param array|Winetoclientorder $orderObj
     *
     * @return array
     */
    private function getOrderArray($orderObj) {
        $orderArray = array();
        $wineArray = array();
        if ($orderObj) {
            if (!is_array($orderObj)) {
                $orders[0] = $orderObj;
            } else {
                $orders = $orderObj;
            }
            foreach ($orders as $order) {
                /** @var Winetoclientorder $order */
                $wine = $this->getWineArray($order->getWine());
                $client = $this->getClientArray($order->getOrder()->getClient());
                $orderArray[] = array(
                    'id'        => $order->getOrder()->getId(),
                    'orderDate' => $order->getOrder()->getOrderdate(),
                    'client'    => !empty($client) ? $client[0] : null
                );
                $wineArray[$order->getOrder()->getId()][] = array(
                    'amount'    => $order->getAmount(),
                    'price'     => $order->getPrice(),
                    'wine'      => $wine[0]);
            }
            foreach ($orderArray as $key=>$orderSorted) {
                $orderArray[$key]['clientOrder'] = $wineArray[$orderSorted['id']];
            }
        }

        return $orderArray;
    }

    /**
     * Erzeugt ein Weinbestellungs Array
     *
     * @param array|Winetoclientorder $orderObj
     *
     * @return array
     */
    private function getWineorderArray($orderObj) {
        $wineArray = array();
        if ($orderObj) {
            if (!is_array($orderObj)) {
                $orders[0] = $orderObj;
            } else {
                $orders = $orderObj;
            }
            foreach ($orders as $order) {
                /** @var Winetoclientorder $order */
                $wine = $this->getWineArray($order->getWine());
                $wineArray[$order->getId()][] = array(
                    'amount'    => $order->getAmount(),
                    'price'     => $order->getPrice(),
                    'wine'      => $wine[0]);
            }
        }

        return $wineArray;
    }

    /**
     * Api Client editieren
     *
     * @param string  $searchCriteria Client ID
     * @param Request $request array(
     *      forename    => String,
     *      surname     => String,
     *      street      => String,
     *      streetno    => String,
     *      city        => String,
     *      zipcode     => Int(5),
     *      phone       => array(
     *          String,
     *          String,
     *          ...
     *      )
     * )
     *
     * @Route(
     *       "/weinverwaltung/api/edit/client/{searchCriteria}",
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "POST", "PUT" }
     * )
     *
     * @return Response json
     */
    public function editClientAction($searchCriteria, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Anfrage')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            /** @var Client $client */
            $client = $clientRepo->findOneBy(array('id' => $searchCriteria));
            if ($client) {
                $newClient = $this->validationClientEditPost($request, $client);
                $editClient = $this->editClient($client, $newClient);
                //Wein für JSON Ausgabe vorbereiten
                $clientArray = $this->getClientArray($editClient);
                if (!empty($clientArray)) {
                    $response = new Response(json_encode($clientArray));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_OK);
                }
            }
        }

        return $response;
    }

    /**
     * Api Wine editieren
     *
     * @param string  $searchCriteria Wine ID
     * @param Request $request array(
     *      available   => Bool,
     *      name        => String,
     *      vineyard    => String,
     *      city        => String,
     *      zipcode     => Int(5),
     *      region      => String,
     *      country     => String,
     *      kind        => String,
     *      type        => String,
     *      vintage     => Int(4),
     *      volume      => Float,
     *      price       => Float,
     *      varietal    => String,String
     * )
     *
     * @Route(
     *       "/weinverwaltung/api/edit/wine/{searchCriteria}",
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "POST", "PUT" }
     * )
     *
     * @return Response json
     */
    public function editWineAction($searchCriteria, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Anfrage')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            /** @var Wine $wine */
            $wine = $wineRepo->findOneBy(array('id' => $searchCriteria));
            if ($wine) {
                $newWine = $this->validationWineEditPost($request, $wine);
                $editWine = $this->editWine($wine, $newWine);
                //Wein für JSON Ausgabe vorbereiten
                $wineArray = $this->getWineArray($editWine);
                if (!empty($wineArray)) {
                    $response = new Response(json_encode($wineArray));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_OK);
                }
            }
        }

        return $response;
    }

    /**
     * Api Order editieren
     *
     * @param string  $orderId Order ID
     * @param Request $request array(
     *      client  => Int,
     *      date    => String,
     *      wine    => array(
     *          amount  => Int,
     *          price   => Float,
     *          wine    => Int
     *      )
     * )
     *
     * @Route(
     *       "/weinverwaltung/api/edit/order/{orderId}",
     *       requirements = {"orderId" = "[0-9]+"},
     *       methods      = { "GET", "POST", "PUT" }
     * )
     *
     * @return Response json
     */
    public function editOrderAction($orderId, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Anfrage')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $clientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientorder');
        $winetoclientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
        if ($orderId != null && is_numeric($orderId)) {
            $clientorder = $clientorderRepo->findOneBy(array('id' => $orderId));
            $winetoclientorder = $winetoclientorderRepo->findBy(array('clientOrder' => $clientorder));
            $newOrder = $this->validationClientOrderPost($request, $clientorder, $winetoclientorder);
            $editedOrder = $this->editOrder($newOrder, $clientorder, $winetoclientorder);
            if (!empty($editedOrder)) {
                $response = new Response(json_encode($editedOrder));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_OK);
            }
        }

        return $response;
    }

    /**
     * Funktion zum Validieren eines POST für einen Kunden
     *
     * @param Request $request
     * @param Client  $client
     *
     * @return array
     */
    private function validationClientEditPost(Request $request, Client $client)
    {
        $newClient['forename']  = $request->get('forename') != null    ? $request->get('forename')            : $client->getForename();
        $newClient['surname']   = $request->get('surname') != null     ? $request->get('surname')             : $client->getSurname();
        $newClient['street']    = $request->get('street') != null      ? $request->get('street')              : $client->getStreet();
        $newClient['streetno']  = $request->get('streetno') != null    ? $request->get('streetno')            : $client->getStreetno();
        $newClient['city']      = $request->get('city') != null        ? $request->get('city')                : $client->getCity()->getName();
        $newClient['zipcode']   = $request->get('zipcode') != null     ? $request->get('zipcode')             : $client->getCity()->getZipcode();
        $newClient['phone']     = $request->get('phone') != null       ? explode(',', $request->get('phone')) : $this->getClientPhoneNumbers($client);
        if ($request->get('zipcode') != null && is_int((int)$request->get('zipcode'))) {
            $newClient['zipcode'] = $request->get('zipcode');
        } else {
            $newClient['zipcode'] = $client->getCity()->getZipcode();
        }

        return $newClient;
    }

    /**
     * Funktion zum Validieren eines POST für eines Weines
     *
     * @param Request $request
     * @param Wine    $wine
     *
     * @return array
     */
    private function validationWineEditPost(Request $request, Wine $wine)
    {
        $newWine['name']        = $request->get('name') != null        ? $request->get('name')                    : $wine->getName();
        $newWine['vineyard']    = $request->get('vineyard') != null    ? $request->get('vineyard')                : $wine->getVineyard()->getName();
        $newWine['city']        = $request->get('city') != null        ? $request->get('city')                    : $wine->getVineyard()->getCity()->getName();
        $newWine['region']      = $request->get('region') != null      ? $request->get('type')                    : $wine->getVineyard()->getRegion()->getName();
        $newWine['country']     = $request->get('country') != null     ? $request->get('type')                    : $wine->getVineyard()->getRegion()->getCountry()->getName();
        $newWine['kind']        = $request->get('kind') != null        ? $request->get('kind')                    : $wine->getWinekind()->getName();
        $newWine['type']        = $request->get('type') != null        ? $request->get('type')                    : $wine->getWinetype()->getName();
        $newWine['varietal']    = $request->get('varietal') != null    ? explode(',', $request->get('varietal'))  : $this->getWineVarietal($wine);
        if ($request->get('available') != null && is_bool($request->get('available'))) {
            $newWine['available'] = $request->get('available');
        } else {
            $newWine['available'] = $wine->getAvailable();
        }
        if ($request->get('vintage') != null && strlen($request->get('vintage')) == 4 && is_int((int)$request->get('vintage'))) {
            $newWine['vintage'] = \DateTime::createFromFormat("Y", $request->get('vintage'));
        } else {
            $newWine['vintage'] = $wine->getVintage();
        }
        if ($request->get('price') != null && is_float((float)$request->get('price'))) {
            $newWine['price'] = $request->get('price');
        } else {
            $newWine['price'] = $wine->getPrice();
        }
        if ($request->get('volume') != null && is_float((float)$request->get('volume'))) {
            $newWine['volume'] = $request->get('volume');
        } else {
            $newWine['volume'] = $wine->getVolume();
        }
        if ($request->get('zipcode') != null && is_int((int)$request->get('zipcode'))) {
            $newWine['zipcode'] = $request->get('zipcode');
        } else {
            $newWine['zipcode'] = $wine->getVineyard()->getCity()->getZipcode();
        }

        return $newWine;
    }

    /**
     * Funktion zum Validieren eines POST für einen Kunden
     *
     * @param Request     $request
     * @param array       $winetoclientorder
     * @param Clientorder $clientorder
     *
     * @return array
     */
    private function validationClientOrderPost(Request $request, Clientorder $clientorder, array $winetoclientorder)
    {
        if ($request->get('client') != null && is_int((int)$request->get('client'))) {
            $newOrder['client'] = $request->get('client');
        } else {
            $newOrder['client'] = $clientorder->getClient()->getId();
        }
        if ($request->get('date') != null && \DateTime::createFromFormat("d.m.Y", $request->get('date'))) {
            $newOrder['date'] = \DateTime::createFromFormat("d.m.Y", $request->get('date'));
        } else {
            $newOrder['date'] = $clientorder->getOrderdate();
        }
        if ($request->get('wine') != null && is_array($request->get('wine'))) {
            $tmpOrders = $request->get('wine');
            foreach ($tmpOrders as $tmpOrder) {
                if ($tmpOrder['id'] != null && is_int((int)$tmpOrder['id'])
                    && $tmpOrder['amount'] != null && is_int((int)$tmpOrder['amount'])
                    && $tmpOrder['price'] != null && is_float((float)$tmpOrder['price'])) {
                    $newOrder['wine'][] = $tmpOrder;
                }
            }
            if (empty($newOrder['wine'])) {
                foreach ($winetoclientorder as $wineorder) {
                    /** @var Winetoclientorder $wineorder */
                    $wine['id'] = $wineorder->getId();
                    $wine['amount'] = $wineorder->getAmount();
                    $wine['price'] = $wineorder->getPrice();
                    $newOrder['wine'][] = $wine;
                }
            }
        }

        return $newOrder;
    }

    /**
     * Editieren eines Kunden
     *
     * @param Client $client
     * @param array  $newClient
     *
     * @return Client
     */
    private function editClient(Client $client, $newClient)
    {
        $em = $this->getDoctrine()->getManager();
        //Suchen bzw erzeugen einer Stadt für einen Kunden
        $cityRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:City');
        $city = $cityRepo->findOneBy(array('name' => $newClient['city'], 'zipcode' => $newClient['zipcode']));
        if (!$city) {
            $city = new City($newClient['city'], $newClient['zipcode']);
            $em->persist($client);
            $em->flush();
        }
        if ($city) {
            //Editieren des Kunden
            $client->setForename($newClient['forename']);
            $client->setSurname($newClient['surname']);
            $client->setStreet($newClient['street']);
            $client->setStreetno($newClient['streetno']);
            $client->setCity($city);
            $em->persist($client);
            $em->flush();
            //Suchen bzw erzeugen von Telefonnummern für einen Kunden
            $clientphoneRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientphone');
            $phonesToDelet = $clientphoneRepo->findBy(array('client' => $client));
            foreach ($newClient['phone'] as $phone) {
                foreach ($phonesToDelet as $key=>$phoneToDelete) {
                    if ($phoneToDelete->getNumber() == $phone) {
                        unset($phonesToDelet[$key]);
                    }
                    $clientphone = $clientphoneRepo->findOneBy(array('number' => $phone, 'client' => $client));
                    if (!$clientphone) {
                        $clientphone = new Clientphone($phone, $client);
                        $em->persist($clientphone);
                        $em->flush();
                    }
                }
            }
            foreach ($phonesToDelet as $phoneToDelete) {
                $em->remove($phoneToDelete);
                $em->flush();
            }
        }

        return $client;
    }

    /**
     * Editieren eines Weines
     *
     * @param Wine  $wine
     * @param array $newWine
     *
     * @return Wine
     */
    private function editWine(Wine $wine, $newWine)
    {
        $em = $this->getDoctrine()->getManager();
        $cityRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:City');
        $city = $cityRepo->findOneBy(array('name' => $newWine['city'], 'zipcode' => $newWine['zipcode']));
        if (!$city) {
            $city = new City($newWine['city'], $newWine['zipcode']);
            $em->persist($city);
            $em->flush();
        }
        $countryRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Country');
        $country = $countryRepo->findOneBy(array('name' => $newWine['country']));
        if (!$country) {
            $country = new Country($newWine['country']);
            $em->persist($country);
            $em->flush();
        }
        if ($country) {
            $regionRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Region');
            $region = $regionRepo->findOneBy(array('name' => $newWine['region']));
            if (!$region) {
                $region = new Region($newWine['region'], $country);
                $em->persist($region);
                $em->flush();
            }
            if ($city && $region) {
                $vineyardRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Vineyard');
                $vineyard = $vineyardRepo->findOneBy(array('name' => $newWine['vineyard'], 'region' => $region, 'city' => $city));
                if (!$vineyard) {
                    $vineyard = new Vineyard($newWine['vineyard'], $city, $region);
                    $em->persist($vineyard);
                    $em->flush();
                }
                $winekindRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winekind');
                $winekind = $winekindRepo->findOneBy(array('name' => $newWine['kind']));
                if (!$winekind) {
                    $winekind = new Winekind($newWine['kind']);
                    $em->persist($winekind);
                    $em->flush();
                }
                $winetypeRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetype');
                $winetype = $winetypeRepo->findOneBy(array('name' => $newWine['type']));
                if (!$winetype) {
                    $winetype = new Winetype($newWine['type']);
                    $em->persist($winetype);
                    $em->flush();
                }
                if ($vineyard && $winekind && $winetype) {
                    $wine->setAvailable($newWine['available']);
                    $wine->setPrice($newWine['price']);
                    $wine->setName($newWine['name']);
                    $wine->setVintage($newWine['vintage']);
                    $wine->setVolume($newWine['volume']);
                    $wine->setVineyard($vineyard);
                    $wine->setWinetype($winetype);
                    $wine->setWinekind($winekind);
                    $em->persist($wine);
                    $em->flush();
                    $winevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winevarietal');
                    $winetowinevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetowinevarietal');
                    foreach ($newWine['varietal'] as $varietal) {
                        $winevarietal = $winevarietalRepo->findOneBy(array('name' => $varietal));
                        if (!$winevarietal) {
                            $winevarietal = new Winevarietal($varietal);
                            $em->persist($winevarietal);
                            $em->flush();
                        }
                        $winetowinevarietal = $winetowinevarietalRepo->findOneBy(array('wine' => $wine, 'winevarietal' => $varietal));
                        if ($winetowinevarietal) {
                            $winetowinevarietal = new Winetowinevarietal($wine, $varietal);
                            $em->persist($winetowinevarietal);
                            $em->flush();
                        }
                    }
                }
            }
        }

        return $wine;
    }

    /**
     * Editieren eines Kunden
     *
     * @param array       $newOrder
     * @param Clientorder $clientorder
     * @param array       $winetoclientorder
     *
     * @return Client
     */
    private function editOrder($newOrder, Clientorder $clientorder, array $winetoclientorder)
    {
        $em = $this->getDoctrine()->getManager();
        $cliendRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        $client = $cliendRepo->findOneBy(array('id' => $newOrder['client']));
        $clientorder->setClient($client);
        $clientorder->setOrderdate($newOrder['date']);
        foreach ($newOrder['wine'] as $wine) {
            $wineSet = false;
            $wine['wine'] = $wineRepo->findOneBy(array('id' => $wine['id']));
            foreach ($winetoclientorder as $key=>$persistedwine) {
                /** @var Winetoclientorder $persistedwine */
                if ($persistedwine->getId() == (int)$wine['id']) {
                    $wineSet = true;
                    $persistedwine->setPrice($wine['price']);
                    $persistedwine->setAmount($wine['amount']);
                    $em->persist($persistedwine);
                    $em->flush();
                    unset($winetoclientorder[$key]);
                } elseif (!$wineSet) {
                    $wineSet = true;
                    $orderedWine = new Winetoclientorder($wine['amount'], $wine['price'], $clientorder, $wine['wine']);
                    $em->persist($orderedWine);
                    $em->flush();
                }
            }
        }
        foreach ($winetoclientorder as $wineToDelete) {
            $em->remove($wineToDelete);
            $em->flush();
        }


        return $clientorder;
    }

    /**
     * Api Client löschen
     *
     * @param string $searchCriteria Client ID
     *
     * @Route(
     *       "/weinverwaltung/api/delete/client/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "DELETE" }
     * )
     *
     * @return Response json
     */
    public function deleteClientAction($searchCriteria)
    {
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Kunden ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $client = $clientRepo->findOneBy(array('id' => $searchCriteria));
            if ($client) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($client);
                $em->flush();
                $response = new Response(json_encode($this->getClientArray($clientRepo->findAll())));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_OK);
            }
        }

        return $response;
    }

    /**
     * Api Clientphone löschen
     *
     * @param string $searchCriteria Clientphone ID
     *
     * @Route(
     *       "/weinverwaltung/api/delete/clientphone/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "DELETE" }
     * )
     *
     * @return Response json
     */
    public function deleteClientphoneAction($searchCriteria)
    {
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Telefon ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $clientphoneRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientphone');
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $clientphone = $clientphoneRepo->findOneBy(array('id' => $searchCriteria));
            if ($clientphone) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($clientphone);
                $em->flush();
                $response = new Response(json_encode($this->getClientArray($clientRepo->findAll())));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_OK);
            }
        }

        return $response;
    }

    /**
     * Api Wein löschen
     *
     * @param string $searchCriteria
     *
     * @Route(
     *       "/weinverwaltung/api/delete/wine/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "DELETE" }
     * )
     *
     * @return Response json
     */
    public function deleteWineAction($searchCriteria)
    {
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Wein ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $wine = $wineRepo->findOneBy(array('id' => $searchCriteria));
            if ($wine) {
                $em = $this->getDoctrine()->getManager();
                $wine->setAvailable(false);
                $em->persist($wine);
                $em->flush();
                $response = new Response(json_encode($this->getWineArray($wineRepo->findAll())));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_OK);
            }
        }

        return $response;
    }

    /**
     * Api Order löschen
     *
     * @param string $orderId Wine ID
     * @param string $wineId Wine ID
     *
     * @Route(
     *       "/weinverwaltung/api/delete/order/{orderId}/wine/{wineId}",
     *       defaults     = {
     *          "orderId" = null,
     *          "wineId"  = null
     *       },
     *       requirements = {
     *          "orderId" = "[0-9]+",
     *          "wineId"  = "[0-9]+"
     *       },
     *       methods      = { "GET", "DELETE" }
     * )
     *
     * @return Response json
     */
    public function deleteOrderAction($orderId, $wineId)
    {
        $clientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientorder');
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        $winetoclientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
        $em = $this->getDoctrine()->getManager();
        $clientorder = $clientorderRepo->findOneBy(array('id' => $orderId));
        if ($wineId != null && is_numeric($wineId)) {
            $wine = $wineRepo->findOneBy(array('id' => $wineId));
            if ($wine && $clientorder) {
                $winetoclientorder = $winetoclientorderRepo->findOneBy(array('wine' => $wine, 'order' => $clientorder));
                if ($winetoclientorder) {
                    $em->remove($winetoclientorder);
                    $em->flush();
                    $response = new Response(json_encode($this->getOrderArray($winetoclientorderRepo->findAll())));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_OK);
                }
            } else {
                $response = new Response(json_encode(array('error' => 'Fehlerhafte Wein ID')));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } else {
            if ($clientorder) {
                $em->remove($clientorder);
                $em->flush();
                $response = new Response(json_encode($this->getOrderArray($winetoclientorderRepo->findAll())));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_OK);
            } else {
                $response = new Response(json_encode(array('error' => 'Fehlerhafte Bestell ID')));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        }

        return $response;
    }

    /**
     * Holen der Telefonnummern eines Kunden
     *
     * @param string $client Client ID
     *
     * @return array
     */
    private function getClientPhoneNumbers($client)
    {
        $clientPhoneRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientphone');
        $clientPhones = $clientPhoneRepo->findBy(array('client' => $client));
        $clientPhoneArray = array();
        if ($clientPhones) {
            foreach ($clientPhones as $clientPhone) {
                /** @var Clientphone $clientPhone */
                $clientPhoneArray[] = $clientPhone->getNumber();
            }
        }

        return $clientPhoneArray;
    }

    /**
     * Holen der Weinrebsorten zum Wein
     *
     * @param String $wine
     *
     * @return array
     */
    private function getWineVarietal($wine)
    {
        $winetowinevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetowinevarietal');
        $winetowinevarietals = $winetowinevarietalRepo->findBy(array('wine' => $wine));
        $winevarietalsArray = array();
        if ($winetowinevarietals) {
            foreach ($winetowinevarietals as $winetowinevarietal) {
                /** @var Winetowinevarietal $winetowinevarietal */
                $winevarietalsArray[] = $winetowinevarietal->getWinevarietal()->getName();
            }
        }

        return $winevarietalsArray;
    }
}
