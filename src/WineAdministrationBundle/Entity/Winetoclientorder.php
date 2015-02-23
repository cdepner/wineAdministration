<?php
    /**
     * Wein zu Kundenbestellung ORM Objekt
     * 
     * Schnittstelle zur Datenbank Tabelle Winetoclientorder
     *
     * @author C. Depner
     */
namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winetoclientorder
 *
 * @ORM\Table(
 *     name="WineToClientOrder",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_Wine_Order",
 *             columns={"wine", "clientOrder"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="FK_Winetoclientorder_Order",
 *             columns={"clientOrder"}
 *         ),
 *         @ORM\Index(
 *             name="FK_Winetoclientorder_Wine",
 *             columns={"wine"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="WinetoclientorderRepository")
 */
class Winetoclientorder
{
    /**
     * Wein zu Kundenbestellung ID
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Wein Anzahl
     * 
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * Wein Preis
     * 
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * Wein
     * 
     * @var Wine
     *
     * @ORM\ManyToOne(targetEntity="Wine")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wine", referencedColumnName="id")
     * })
     */
    private $wine;

    /**
     * Kundenbestellung
     * 
     * @var Clientorder
     *
     * @ORM\ManyToOne(targetEntity="Clientorder", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clientOrder", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $clientOrder;

    /**
     * Konstruktor
     * 
     * @param int         $amount
     * @param float       $price
     * @param Clientorder $order
     * @param Wine        $wine
     */
    public function __construct($amount, $price, Clientorder $order, Wine $wine)
    {
        $this->setAmount($amount);
        $this->setPrice($price);
        $this->setOrder($order);
        $this->setWine($wine);
    }

    /**
     * Gibt die ID einer Wein-zu-Kundenbestellung zurück
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt die Menge eines Weines einer Wein-zu-Kundenbestellung zurück
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Setzt die Menge eines Weines einer Wein-zu-Kundenbestellung
     *
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Gibt den Preis eines Weines einer Wein-zu-Kundenbestellung zurück
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Setzt den Preis eines Weines einer Wein-zu-Kundenbestellung
     *
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Gibt die Kundenbestellung einer Wein-zu-Kundenbestellung zurück
     *
     * @return Clientorder
     */
    public function getOrder()
    {
        return $this->clientOrder;
    }

    /**
     * Setzt eine Kundenbestellung einer Wein-zu-Kundenbestellung
     *
     * @param Clientorder $order
     */
    public function setOrder(Clientorder $order)
    {
        $this->clientOrder = $order;
    }

    /**
     * Gibt einen Wein einer Wein-zu-Kundenbestellung zurrück
     *
     * @return Wine
     */
    public function getWine()
    {
        return $this->wine;
    }

    /**
     * Setzt einen Wein einer Wein-zu-Kundenbestellung
     *
     * @param Wine $wine
     */
    public function setWine(Wine $wine)
    {
        $this->wine = $wine;
    }


}
