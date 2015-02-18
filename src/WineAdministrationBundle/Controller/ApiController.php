<?php

namespace WineAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WineAdministrationBundle\Entity\City;
use WineAdministrationBundle\Entity\Client;
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

class ApiController extends Controller
{


    /**
     * Api Client hinzufügen
     *
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/api/add/client",
     *       methods = { "GET", "POST" }
     * )
     *
     * @return Response json
     */
    public function addClientAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            //Default Response für Fehlerhaften Post
            $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $failure = false;
            //Post überprüfung
            $newClient['forename']  = $request->request->get('forename') != null    ? $request->request->get('forename')            : !$failure = true;
            $newClient['surname']   = $request->request->get('surname') != null     ? $request->request->get('surname')             : !$failure = true;
            $newClient['street']    = $request->request->get('street') != null      ? $request->request->get('street')              : !$failure = true;
            $newClient['streetno']  = $request->request->get('streetno') != null    ? $request->request->get('streetno')            : !$failure = true;
            $newClient['city']      = $request->request->get('city') != null        ? $request->request->get('city')                : !$failure = true;
            $newClient['phone']     = $request->request->get('phone') != null       ? explode(',', $request->request->get('phone')) : !$failure = true;
            if ($request->request->get('zipcode') != null && is_int($request->request->get('zipcode'))) {
                $newClient['zipcode'] = $request->request->get('zipcode');
            } else {
                $newClient['zipcode'] = false;
                $failure = true;
            }
            if (!$failure) {
                //Kunde in die Datenbank schreiben
                $client = $this->addClient($newClient);
                if ($client != null) {
                    //Kunde für JSON Ausgabe vorbereiten
                    $usersArray = $this->getClientArray($client);
                    if (!empty($usersArray)) {
                        $response = new Response(json_encode($usersArray));
                        $response->headers->set('Content-Type', 'application/json');
                        $response->setStatusCode(Response::HTTP_OK);
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

        return $this->redirect($this->generateUrl('wineadministration_api_showclient', array('searchCriteria' => null)));
    }

    /**
     * Api Wein erstellen
     *
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/api/add/wine",
     *       methods = { "GET", "POST" }
     * )
     *
     * @return Response json
     */
    public function addWineAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            //Default Response für Fehlerhaften Post
            $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $failure = false;
            //Post überprüfung
            $newWine['name']        = $request->request->get('name') != null        ? $request->request->get('name')                    : !$failure = true;
            $newWine['vinyard']     = $request->request->get('vinyard') != null     ? $request->request->get('vinyard')                 : !$failure = true;
            $newWine['city']        = $request->request->get('city') != null        ? $request->request->get('city')                    : !$failure = true;
            $newWine['region']      = $request->request->get('region') != null      ? $request->request->get('type')                    : !$failure = true;
            $newWine['country']     = $request->request->get('country') != null     ? $request->request->get('type')                    : !$failure = true;
            $newWine['kind']        = $request->request->get('kind') != null        ? $request->request->get('kind')                    : !$failure = true;
            $newWine['type']        = $request->request->get('type') != null        ? $request->request->get('type')                    : !$failure = true;
            $newWine['varietal']    = $request->request->get('varietal') != null    ? explode(',', $request->request->get('varietal'))  : !$failure = true;
            if ($request->request->get('available') != null && is_bool($request->request->get('available'))) {
                $newWine['available'] = $request->request->get('available');
            } else {
                $failure = true;
            }
            if ($request->request->get('vintage') != null && strlen($request->request->get('vintage')) == 4 && is_int($request->request->get('vintage'))) {
                $newWine['vintage'] = \DateTime::createFromFormat("Y", $request->request->get('vintage'));
            } else {
                $newWine['vintage'] = false;
                $failure = true;
            }
            if ($request->request->get('price') != null && is_float($request->request->get('price'))) {
                $newWine['price'] = $request->request->get('price');
            } else {
                $newWine['price'] = false;
                $failure = true;
            }
            if ($request->request->get('volume') != null && is_float($request->request->get('volume'))) {
                $newWine['volume'] = $request->request->get('volume');
            } else {
                $newWine['volume'] = false;
                $failure = true;
            }
            if ($request->request->get('zipcode') != null && is_int($request->request->get('zipcode'))) {
                $newWine['zipcode'] = $request->request->get('zipcode');
            } else {
                $newWine['zipcode'] = false;
                $failure = true;
            }
            if (!$failure) {
                //Wein in die Datenbank schreiben
                $wine = $this->addWine($newWine);
                if ($wine != null) {
                    //Wein für JSON Ausgabe vorbereiten
                    $wineArray = $this->getWineArray($wine);
                    if (!empty($wineArray)) {
                        $response = new Response(json_encode($wineArray));
                        $response->headers->set('Content-Type', 'application/json');
                        $response->setStatusCode(Response::HTTP_OK);
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

        return $this->redirect($this->generateUrl('wineadministration_api_showwine', array('searchCriteria' => null)));
    }

    /**
     * Api Bestellung erstellen
     *
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/api/add/order",
     *       methods = { "GET", "POST" }
     * )
     *
     * @return Response json
     */
    public function addOrderAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            //Default Response für Fehlerhaften Post
            $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $failure = false;
            //Post überprüfung
            if ($request->request->get('wine') != null && is_array($request->request->get('wine'))) {
                $orders = $request->request->get('wine');
                $newOrder['wine'] = array();
                foreach ($orders as $order) {
                    if (!empty($order['id'])) {
                        $wine['id'] = $order['id'];
                    } else {
                        $wine['id'] = false;
                        $failure = true;
                    }
                    if (!empty($order['amount'])) {
                        $wine['amount'] = $order['amount'];
                    } else {
                        $wine['amount'] = false;
                        $failure = true;
                    }
                    if (!empty($order['price'])) {
                        $wine['price'] = $order['price'];
                    } else {
                        $wine['price'] = false;
                        $failure = true;
                    }
                    $newOrder['wine'][] = $wine;
                }
            } else {
                $newOrder['wine'] = false;
                $failure = true;
            }
            if ($request->request->get('client') != null && is_int($request->request->get('client'))) {
                $newOrder['client'] = $request->request->get('client');
            } else {
                $newOrder['client'] = false;
                $failure = true;
            }
            if ($request->request->get('date') != null && \DateTime::createFromFormat('Y-m-d',$request->request->get('date'))) {
                $newOrder['date'] = \DateTime::createFromFormat("Y-m-d", $request->request->get('date'));
            } else {
                $newOrder['date'] = false;
                $failure = true;
            }
            if (!$failure) {
                //Wein in die Datenbank schreiben
                $order = $this->addOrder($newOrder);
                if ($order != null) {
                    //Wein für JSON Ausgabe vorbereiten
                    $orderArray = $this->getOrderArray($order);
                    if (!empty($orderArray)) {
                        $response = new Response(json_encode($orderArray));
                        $response->headers->set('Content-Type', 'application/json');
                        $response->setStatusCode(Response::HTTP_OK);
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

        return $this->redirect($this->generateUrl('wineadministration_api_showorder', array('searchCriteria' => null)));
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
                $clientphone = $clientphoneRepo->findOneBy(array('number' => $clientArray['phone']));
                if (!$clientphone) {
                    $clientphone = new Clientphone($clientArray['phone'], $client);
                    $em->persist($clientphone);
                    $em->flush();
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
                $vineyard = $vineyardRepo->findOneBy(array('name' => $wineArray['vinyard'], 'region' => $region, 'city' => $city));
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
                        'available' => $wineArray['available'],
                        'price'     => $wineArray['price'],
                        'name'      => $wineArray['name'],
                        'vintage'   => $wineArray['vintage'],
                        'volume'    => $wineArray['volume'],
                        'winekind'  => $winekind,
                        'winetype'  => $winetype,
                        'vinyard'   => $vineyard
                    ));
                    if (!$wine) {
                        $wine = new Wine($wineArray['available'], $wineArray['price'], $wineArray['name'], $wineArray['vintage'], $wineArray['volume'], $vineyard, $winekind, $winetype);
                        $em->persist($wine);
                        $em->flush();
                    }
                    if ($wine) {
                        $winevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winevarietal');
                        $winetowinevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winevarietal');
                        foreach ($wineArray['varietal'] as $varietal) {
                            $winevarietal = $winevarietalRepo->findOneBy(array('varietal' => $varietal));
                            if (!$winevarietal) {
                                $winevarietal = new Winevarietal($varietal);
                                $em->persist($winevarietal);
                                $em->flush();
                            }
                            $winetowinevarietal = $winetowinevarietalRepo->findOneBy(array('varietal' => $varietal, $wine));
                            if (!$winetowinevarietal) {
                                $winetowinevarietal = new Winetowinevarietal($wine, $varietal);
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
     * @return Clientorder
     */
    private function addOrder(array $orderArray)
    {
        $em = $this->getDoctrine()->getManager();
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        $client = $clientRepo->findOneBy(array('id' => $orderArray['client']));
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        $wineArray = array();
        foreach ($orderArray['wine'] as $wine) {
            $tmpWine['wine']    = $wineRepo->findOneBy(array('id' => $wine['id']));
            $tmpWine['amount']  = $wine['amount'];
            $tmpWine['price']   = $wine['price'];
            $wineArray[]        = $tmpWine;
        }

        $clientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientorder');
        $clientorder = $clientorderRepo->findOneBy(array('client' => $client, 'date' => $orderArray['date']));
        if (!$clientorder) {
            $clientorder = new Clientorder($orderArray['date'], $client);
            $em->persist($clientorder);
            $em->flush();
        }
        if ($clientorder) {
            foreach ($wineArray as $wineorder) {
                $winetoclientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
                $winetoclientorder = $winetoclientorderRepo->findOneBy(array('id' => $wineorder['id']));
                if (!$winetoclientorder) {
                    $clientorder = new Winetoclientorder($wineorder['amount'], $wineorder['price'], $clientorder, $wineorder['wine']);
                    $em->persist($clientorder);
                    $em->flush();
                }
            }

            return $clientorder;
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
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
        /** @var WinetoclientorderRepository $winetoclientorderRepository */
        $winetoclientorderRepository = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $orders = $winetoclientorderRepository->findBy(array('id' => $searchCriteria));
        } elseif ($searchCriteria != null) {
            $orders = $winetoclientorderRepository->getOrderByName($searchCriteria);
        } else {
            $orders = $winetoclientorderRepository->findAll();
        }
        $orderSorted = $this->getOrderArray($orders);
        $jsonOrder = json_encode($orderSorted);
        $response = new Response($jsonOrder);
        $response->headers->set('Content-Type', 'application/json');
        if (empty($orderArray)) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
                    'id'        => $order->getId(),
                    'orderDate' => $order->getOrder()->getOrderdate(),
                    'client'    => !empty($client) ? $client[0] : null
                );
                $wineArray[$order->getId()][] = array(
                    'amount'    => $order->getAmount(),
                    'price'     => $order->getPrice(),
                    'wine'      => $wine[0]);
            }
            foreach ($orderArray as $key=>$orderSorted) {
                $orderArray[$key]['order'] = $wineArray[$orderSorted['id']];
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
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/api/edit/client/{searchCriteria}",
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "POST" }
     * )
     *
     * @return Response json
     */
    public function editClientAction($searchCriteria, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafte ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            /** @var Client $client */
            $client = $clientRepo->findOneBy(array('id' => $searchCriteria));
            if ($client && $request->getMethod() == 'POST') {
                $newClient['forename']  = $request->request->get('forename') != null    ? $request->request->get('forename')            : $client->getForename();
                $newClient['surname']   = $request->request->get('surname') != null     ? $request->request->get('surname')             : $client->getSurname();
                $newClient['street']    = $request->request->get('street') != null      ? $request->request->get('street')              : $client->getStreet();
                $newClient['streetno']  = $request->request->get('streetno') != null    ? $request->request->get('streetno')            : $client->getStreetno();
                $newClient['city']      = $request->request->get('city') != null        ? $request->request->get('city')                : $client->getCity()->getName();
                $newClient['zipcode']   = $request->request->get('zipcode') != null     ? $request->request->get('zipcode')             : $client->getCity()->getZipcode();
                $newClient['phone']     = $request->request->get('phone') != null       ? explode(',', $request->request->get('phone')) : $this->getClientPhoneNumbers($client);
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
                    foreach ($client['phone'] as $phone) {
                        $clientphone = $clientphoneRepo->findOneBy(array('clientphone' => $phone, 'client' => $client));
                        if (!$clientphone) {
                            $clientphone = new Clientphone($phone, $client);
                            $em->persist($clientphone);
                            $em->flush();
                        }
                    }
                    //Wein für JSON Ausgabe vorbereiten
                    $clientArray = $this->getClientArray($client);
                    if (!empty($clientArray)) {
                        $response = new Response(json_encode($clientArray));
                        $response->headers->set('Content-Type', 'application/json');
                        $response->setStatusCode(Response::HTTP_OK);
                    }
                }
                //Ausgabe für Fehlerhaften Post
                $response = new Response(json_encode($newClient));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
        }

        return $response;
    }

    /**
     * Api Wine editieren
     *
     * @param string  $searchCriteria Wine ID
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/api/edit/wine",
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "POST" }
     * )
     *
     * @return Response json
     */
    public function editWineAction($searchCriteria, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafte ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            /** @var Wine $wine */
            $wine = $wineRepo->findOneBy(array('id' => $searchCriteria));
            if ($wine && $request->getMethod() == 'POST') {

                $newWine['name']        = $request->request->get('name') != null        ? $request->request->get('name')                    : $wine->getName();
                $newWine['vinyard']     = $request->request->get('vinyard') != null     ? $request->request->get('vinyard')                 : $wine->getVineyard()->getName();
                $newWine['city']        = $request->request->get('city') != null        ? $request->request->get('city')                    : $wine->getVineyard()->getCity()->getName();
                $newWine['region']      = $request->request->get('region') != null      ? $request->request->get('type')                    : $wine->getVineyard()->getRegion()->getName();
                $newWine['country']     = $request->request->get('country') != null     ? $request->request->get('type')                    : $wine->getVineyard()->getRegion()->getCountry()->getName();
                $newWine['kind']        = $request->request->get('kind') != null        ? $request->request->get('kind')                    : $wine->getWinekind();
                $newWine['type']        = $request->request->get('type') != null        ? $request->request->get('type')                    : $wine->getWinetype();
                $newWine['varietal']    = $request->request->get('varietal') != null    ? explode(',', $request->request->get('varietal'))  : $this->getWineVarietal($wine);
                if ($request->request->get('available') != null && is_bool($request->request->get('available'))) {
                    $newWine['available'] = $request->request->get('available');
                } else {
                    $newWine['available'] = $wine->getAvailable();
                }
                if ($request->request->get('vintage') != null && strlen($request->request->get('vintage')) == 4 && is_int($request->request->get('vintage'))) {
                    $newWine['vintage'] = \DateTime::createFromFormat("Y", $request->request->get('vintage'));
                } else {
                    $newWine['vintage'] = $wine->getVintage();
                }
                if ($request->request->get('price') != null && is_float($request->request->get('price'))) {
                    $newWine['price'] = $request->request->get('price');
                } else {
                    $newWine['price'] = $wine->getPrice();
                }
                if ($request->request->get('volume') != null && is_float($request->request->get('volume'))) {
                    $newWine['volume'] = $request->request->get('volume');
                } else {
                    $newWine['volume'] = $wine->getVolume();
                }
                if ($request->request->get('zipcode') != null && is_int($request->request->get('zipcode'))) {
                    $newWine['zipcode'] = $request->request->get('zipcode');
                } else {
                    $newWine['zipcode'] = $wine->getVineyard()->getCity()->getZipcode();
                }

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
                        $vineyard = $vineyardRepo->findOneBy(array('name' => $newWine['vinyard'], 'region' => $region, 'city' => $city));
                        if (!$vineyard) {
                            $vineyard = new Vineyard($newWine['vineyard'], $city, $region);
                            $em->persist($vineyard);
                            $em->flush();
                        }
                        $winekindRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winekind');
                        $winekind = $winekindRepo->findOneBy(array('name' => $newWine['kind']));
                        if (!$winekind) {
                            $winekind = new Winekind($newWine['name']);
                            $em->persist($winekind);
                            $em->flush();
                        }
                        $winetypeRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetype');
                        $winetype = $winetypeRepo->findOneBy(array('name' => $newWine['type']));
                        if (!$winetype) {
                            $winetype = new Winetype($newWine['name']);
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
                            foreach ($wine['varietal'] as $varietal) {
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
                            //Wein für JSON Ausgabe vorbereiten
                            $wineArray = $this->getWineArray($wine);
                            if (!empty($wineArray)) {
                                $response = new Response(json_encode($wineArray));
                                $response->headers->set('Content-Type', 'application/json');
                                $response->setStatusCode(Response::HTTP_OK);
                            }
                        }
                    }
                }
                //Ausgabe für Fehlerhaften Post
                $response = new Response(json_encode($newWine));
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
        }

        return $response;
    }

    /**
     * Api Order editieren
     *
     * @param string  $orderId Order ID
     * @param string  $wineId Wine ID
     * @param Request $request
     *
     * @Route(
     *       "/weinverwaltung/api/edit/order/{orderId}/wine/{wineId}",
     *       defaults     = {
     *          "wineId" = null
     *       },
     *       requirements = {
     *          "orderId" = "[0-9]+",
     *          "wineId" = "[0-9]+"
     *       },
     *       methods      = { "GET", "POST" }
     * )
     *
     * @return Response json
     */
    public function editOrderAction($orderId, $wineId, Request $request)
    {
        //Default Response für Fehlerhaften Post
        $response = new Response(json_encode(array('error' => 'Fehlerhafter Post')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $clientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientorder');
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        $winetoclientorderRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
        if ($orderId != null && is_numeric($orderId)) {
            $clientorder = $clientorderRepo->findOneBy(array('id' => $orderId));
            if ($wineId != null && is_numeric($wineId)) {
                $wine = $wineRepo->findOneBy(array('id' => $wineId));
                if ($wine && $clientorder) {
                    $winetoclientorder = $winetoclientorderRepo->findOneBy(array('order' => $clientorder, 'wine' => $wine));
                    if ($winetoclientorder) {
                        if ($request->request->get('amount') != null && is_int($request->request->get('amount'))) {
                            $newOrder['amount'] = $request->request->get('amount');
                        } else {
                            $newOrder['amount'] = $winetoclientorder->getAmount();
                        }
                        if ($request->request->get('price') != null && is_float($request->request->get('price'))) {
                            $newOrder['price'] = $request->request->get('price');
                        } else {
                            $newOrder['price'] = $winetoclientorder->getPrice();
                        }
                        $winetoclientorder->setAmount($newOrder['amount']);
                        $winetoclientorder->setPrice($newOrder['price']);
                    } else {
                        $failure = false;
                        if ($request->request->get('amount') != null && is_int($request->request->get('amount'))) {
                            $newOrder['amount'] = $request->request->get('amount');
                        } else {
                            $newOrder['amount'] = false;
                            $failure = true;
                        }
                        if ($request->request->get('price') != null && is_float($request->request->get('price'))) {
                            $newOrder['price'] = $request->request->get('price');
                        } else {
                            $newOrder['price'] = false;
                            $failure = true;
                        }
                        if (!$failure) {
                            $winetoclientorder = new Winetoclientorder($newOrder['amount'], $newOrder['price'], $clientorder, $wine);
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($winetoclientorder);
                            $em->flush();
                            //Wein für JSON Ausgabe vorbereiten
                            $orderArray = $this->getOrderArray($winetoclientorder);
                            if (!empty($orderArray)) {
                                $response = new Response(json_encode($orderArray));
                                $response->headers->set('Content-Type', 'application/json');
                                $response->setStatusCode(Response::HTTP_OK);
                            }
                        } else {
                            //Ausgabe für Fehlerhaften Post
                            $response = new Response(json_encode($newOrder));
                            $response->headers->set('Content-Type', 'application/json');
                            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                        }
                    }
                }
            }
        }

        return $response;
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
     *       methods      = { "GET" }
     * )
     *
     * @return Response json
     */
    public function deleteClientAction($searchCriteria)
    {
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Kunden ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
     *       methods      = { "GET" }
     * )
     *
     * @return Response json
     */
    public function deleteClientphoneAction($searchCriteria)
    {
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Telefon ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
     *       methods      = { "GET" }
     * )
     *
     * @return Response json
     */
    public function deleteWineAction($searchCriteria)
    {
        $response = new Response(json_encode(array('error' => 'Fehlerhafte Wein ID')));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
     *       methods      = { "GET" }
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
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
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
