<?php

namespace WineAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    /**
     * Api User anfrage
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
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $clients = $clientRepo->findById($searchCriteria);
        } elseif ($searchCriteria != null && strpos($searchCriteria, '_')) {
            $criteria = explode('_', $searchCriteria);
            $clients = $clientRepo->getClientByFullName($criteria);
        } elseif ($searchCriteria != null) {
            $criteria = explode('_', $searchCriteria);
            $clients = $clientRepo->getClientByName($searchCriteria);
        } else {
            $clients = $clientRepo->findAll();
        }
        $usersArray = $this->getClientArray($clients);
        $jsonUser = json_encode($usersArray);
        $response = new Response($jsonUser);
        $response->headers->set('Content-Type', 'application/json');
        if (empty($usersArray)) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } else {
            $response->setStatusCode(Response::HTTP_OK);
        }

        return $response;
    }

    /**
     * Api Client editieren
     * 
     * @Route(
     *       "/weinverwaltung/api/edit/client/{searchCriteria}",
     *       defaults     = { "searchCriteria" = null },
     *       requirements = { "searchCriteria" = "[0-9]+" },
     *       methods      = { "GET", "POST" }
     * )  
     *    
     * @return Response json
     */
    public function editClientAction($searchCriteria, Request $request)
    {
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $client = $clientRepo->findOneById($searchCriteria);
            if ($client && $request->getMethod() == 'POST') {
                $newClient['forename'] = $request->request->get('forename') != null ? $request->request->get('forename') : $client->getForename();
                $newClient['surname'] = $request->request->get('surname') != null ? $request->request->get('surname') : $client->getSurname();
                $newClient['street'] = $request->request->get('street') != null ? $request->request->get('street') : $client->getStreet();
                $newClient['streetno'] = $request->request->get('streetno') != null ? $request->request->get('streetno') : $client->getStreetno();
                $newClient['city'] = $request->request->get('city') != null ? $request->request->get('city') : $client->getCity()->getName();
                $newClient['zipcode'] = $request->request->get('zipcode') != null ? $request->request->get('zipcode') : $client->getCity()->getZipcode();
                $newClient['phone'] = explode(',',$request->request->get('phone')) != null ? $request->request->get('phone') : $this->getClientPhoneNumbers($client);
                /*
                $client->setForename();
                $client->setSurname();
                $client->setStreet();
                $client->setStreetno();
                $client->setCity();
                $client->setZipcode();
                $phone->setPhone();
                 */
            }
        }

        return $this->showClientAction(null);
    }

    /**
     * Api Client löschen
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
        $clientRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Client');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $client = $clientRepo->findOneById($searchCriteria);
            if ($client) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($client);
                $em->flush();
            }
        }

        return $this->showClientAction(null);
    }

    /**
     * Api Clientphone löschen
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
        $clientphoneRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientphone');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $clientphone = $clientphoneRepo->findOneById($searchCriteria);
            if ($clientphone) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($clientphone);
                $em->flush();
            }
        }

        return $this->showClientAction(null);
    }
    
    /**
     * Holen der Telefonnummern eines Kunden
     * 
     * @param String $client
     * 
     * @return array
     */
    public function getClientPhoneNumbers($client)
    {
        $clientPhoneRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientphone');
        $clientPhones = $clientPhoneRepo->findByClient($client);
        $clientPhoneArray = array();
        if ($clientPhones) {
            foreach($clientPhones as $clientPhone) {
                $clientPhoneArray[] = $clientPhone->getNumber();
            }
        }

        return $clientPhoneArray;
    }
    
    /**
     * Holen der Telefonnummern eines Kunden
     * 
     * @param String $client
     * 
     * @return array
     */
    public function setClientPhonenumbers($client, array $phonenumbers)
    {
        $clientPhoneRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Clientphone');
        $clientPhones = $clientPhoneRepo->findByClient($client);
        $clientPhoneArray = array();
        if ($clientPhones) {
            foreach ($clientPhones as $clientPhone) {
                foreach ($phonenumbers as $id=>$phonenumber) {
                    $phonePersisted = false;
                    if ($clientPhone->getNumber() == $phonenumber) {
                        unset($phonenumbers);
                    }
                }
            }
        }
        if (!empty($phonenumbers)) {
            $this->addPhonenumber($client, $phonenumbers);
        }

        return $this->getClientPhoneNumbers($client);
    }

    /**
     * Api Wein anfrage
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
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $wines = $wineRepo->findById($searchCriteria);
        } elseif ($searchCriteria != null) {
            $wines = $wineRepo->getWineByName($searchCriteria);
        } else {
            $wines = $wineRepo->findAll();
        }
        $winesArray = $this->getWineArray($wines);
        $jsonWine = json_encode($winesArray);
        $response = new Response($jsonWine);
        $response->headers->set('Content-Type', 'application/json');
        if (empty($winesArray)) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } else {
            $response->setStatusCode(Response::HTTP_OK);
        }

        return $response;
    }

    /**
     * Api Wein löschen
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
        $wineRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Wine');
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $wine = $wineRepo->findOneById($searchCriteria);
            if ($wine) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($wine);
                $em->flush();
            }
        }

        return $this->showWineAction(null);
    }
    
    /**
     * Holen der Weinrebsorten zum Wein
     * 
     * @param String $wine
     * 
     * @return array
     */
    public function getWineVarietal($wine)
    {
        $winetowinevarietalRepo = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetowinevarietal');
        $winetowinevarietals = $winetowinevarietalRepo->findByWine($wine);
        $winevarietalsArray = array();
        if ($winetowinevarietals) {
            foreach($winetowinevarietals as $winetowinevarietal) {
                $winevarietalsArray[] = $winetowinevarietal->getWinevarietal()->getName();
            }
        }

        return $winevarietalsArray;
    }


    /**
     * Api Bestell Anfrage
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
    public function orderAction($searchCriteria)
    {
        $winetoclientorderRepository = $this->getDoctrine()->getRepository('WineAdministrationBundle:Winetoclientorder');
        
        if ($searchCriteria != null && is_numeric($searchCriteria)) {
            $orders = $winetoclientorderRepository->findById($searchCriteria);
        } elseif ($searchCriteria != null) {
            $orders = $winetoclientorderRepository->getOrderByName($searchCriteria);
        } else {
            $orders = $winetoclientorderRepository->findAll();
        }
        $orderArray = array();
        $orderSorted = array();
        if ($orders) {
            foreach($orders as $order) {
                $orderArray[] = array(
                    'id'        => $order->getId(),
                    'amount'    => $order->getAmount(),
                    'price'     => $order->getPrice(),
                    'orderId'   => $order->getOrder()->getId(),
                    'wine'      => $this->getWineArray($order->getWine())[0],
                    'orderdate' => $order->getOrder()->getOrderdate(),
                    'client'    => $this->getClientArray($order->getOrder()->getClient())
                );
            }
            foreach($orderArray as $orderSort) {
                $orderId = $orderSort['orderId'];
                $client = $orderSort['client'][0];
                unset($orderSort['orderId']);
                unset($orderSort['client']);
                $orderSorted[] = array(
                    'id'        => $orderId,
                    'client'    =>$client,
                    'orders'    => array($orderSort)
                );
            }
        }
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
     * @param array|\Wine $winesObj
     * 
     * @return array
     */
    public function getWineArray($winesObj)
    {
        $winesArray = array();
        if ($winesObj) {
            if (!is_array($winesObj)) {
                $wines[0] = $winesObj;
            } else {
                $wines = $winesObj;
            }
            foreach($wines as $wine) {
                $winesArray[] = array(
                    'id'        => $wine->getId(),
                    'available' => $wine->getAvailable(),
                    'price'     => $wine->getPrice(),
                    'name'      => $wine->getName(),
                    'vintage'   => $wine->getVintage()->format('Y'),
                    'volume'    => $wine->getVolume(),
                    'vinyard'   => $wine->getVinyard()->getName(),
                    'city'      => $wine->getVinyard()->getCity()->getName(),
                    'vinyard'   => $wine->getVinyard()->getRegion()->getName(),
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
     * @param array|\Client $clientsObj
     * 
     * @return array
     */
    public function getClientArray($clientsObj)
    {
        $usersArray = array();
        if ($clientsObj) {
            if (!is_array($clientsObj)) {
                $clients[0] = $clientsObj;
            } else {
                $clients = $clientsObj;
            }
            foreach($clients as $client) {
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
}
