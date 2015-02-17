<?php

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
 *             columns={"wine", "order"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="FK_Winetoclientorder_Order",
 *             columns={"order"}
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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var Wine
     *
     * @ORM\ManyToOne(targetEntity="Wine")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wine", referencedColumnName="id")
     * })
     */
    private $wine;

    /**
     * @var Clientorder
     *
     * @ORM\ManyToOne(targetEntity="Clientorder", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $order;

    /**
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
        return $this->order;
    }

    /**
     * Setzt eine Kundenbestellung einer Wein-zu-Kundenbestellung
     *
     * @param Clientorder $order
     */
    public function setOrder(Clientorder $order)
    {
        $this->order = $order;
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
