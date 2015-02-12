<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientphone
 *
 * @ORM\Table(name="ClientPhone", uniqueConstraints={@ORM\UniqueConstraint(name="number_UNIQUE", columns={"number"})}, indexes={@ORM\Index(name="IDX_8FAB8477C7440455", columns={"client"})})
 * @ORM\Entity
 */
class Clientphone
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
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=20, nullable=false)
     */
    private $number;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    private $client;
    
    public function __construct($number, \Client $client) {
        $this->setNumber($number);
        $this->setClient($client);
    }

    public function getNumber() {
        return $this->number;
    }

    public function getClient() {
        return $this->client;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function setClient(\Client $client) {
        $this->client = $client;
    }
}
