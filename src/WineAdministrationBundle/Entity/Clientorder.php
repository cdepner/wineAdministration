<?php
    /**
     * Kundenbestellung ORM Objekt
     * 
     * Schnittstelle zur Datenbank Tabelle Clientorder
     *
     * @author C. Depner
     */
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
     * Kundenbestellung ID
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Datum des Kundenbestellung
     * 
     * @var \DateTime
     *
     * @ORM\Column(name="orderDate", type="date", nullable=false)
     */
    private $orderdate;

    /**
     * Kunde
     * 
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $client;

    /**
     * Konstruktor
     * 
     * @param \DateTime $orderdate
     * @param Client    $client
     */
    public function __construct(\DateTime $orderdate, Client $client)
    {
        $this->setOrderdate($orderdate);
        $this->setClient($client);
    }

    /**
     * Gibt die ID einer Kundenbestellung zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt das Bestelldatum einer Kundenbestellung zurück
     *
     * @return \DateTime
     */
    public function getOrderdate()
    {
        return $this->orderdate;
    }

    /**
     * Setzt das Bestelldatum einer Kundenbestellung
     *
     * @param \DateTime $orderdate
     */
    public function setOrderdate(\DateTime $orderdate)
    {
        $this->orderdate = $orderdate;
    }

    /**
     * Gibt den Kunden einer Kundenbestellung zurück
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Setzt den Kunden einer Kundenbestellung
     *
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }
}
