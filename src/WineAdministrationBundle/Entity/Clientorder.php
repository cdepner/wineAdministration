<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientorder
 *
 * @ORM\Table(name="ClientOrder", indexes={@ORM\Index(name="IDX_3ECD8032C7440455", columns={"client"})})
 * @ORM\Entity
 */
class Clientorder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="orderDate", type="date", nullable=false)
     */
    private $orderdate;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    private $client;

    public function __construct(\DateTime $orderdate, \Client $client) {
        $this->setOrderdate($orderdate);
        $this->setClient($client);
    }

    public function getOrderdate() {
        return $this->orderdate;
    }

    public function getClient() {
        return $this->client;
    }

    public function setOrderdate(\DateTime $orderdate) {
        $this->orderdate = $orderdate;
    }

    public function setClient(\Client $client) {
        $this->client = $client;
    }
}
