<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientorder
 *
 * @ORM\Table(
 *     name="ClientOrder",
 *     indexes={
 *         @ORM\Index(
 *             name="FK_Clientorder_Client",
 *             columns={"client"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="ClientorderRepository")
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
     * @ORM\ManyToOne(targetEntity="Client", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $client;

    public function __construct(\DateTime $orderdate, $client) {
        $this->setOrderdate($orderdate);
        $this->setClient($client);
    }

    public function getId() {
        return $this->id;
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

    public function setClient($client) {
        $this->client = $client;
    }
}
