<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientphone
 *
 * @ORM\Table(
 *     name="ClientPhone",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_number",
 *             columns={"number"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *              name="FK_Clientphone_Client",
 *              columns={"client"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="ClientphoneRepository")
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
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $client;

    /**
     * @param string $number
     * @param Client $client
     */
    public function __construct($number, Client $client)
    {
        $this->setNumber($number);
        $this->setClient($client);
    }

    /**
     * Gibt die ID einer Kundentelefonnummer zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt die Nummer einer Kundentelefonnummer zurück
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Setzt die Nummer einer Kundentelefonnummer
     *
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Gibt den Kunden einer Kundentelefonnummer zurück
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Setzt den Kunden einer Kundentelefonnummer
     *
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }
}
