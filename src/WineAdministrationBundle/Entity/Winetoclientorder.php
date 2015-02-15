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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="volume", type="float", precision=10, scale=0, nullable=false)
     */
    private $volume;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var \Wine
     *
     * @ORM\ManyToOne(targetEntity="Wine", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wine", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $wine;

    /**
     * @var \Clientorder
     *
     * @ORM\ManyToOne(targetEntity="Clientorder", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $order;

    public function __construct($amount, $price, $order, $wine) {
        $this->setAmount($amount);
        $this->setPrice($price);
        $this->setOrder($order);
        $this->setWine($wine);
    }

    public function getId() {
        return $this->id;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getWine() {
        return $this->wine;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function setWine($wine) {
        $this->wine = $wine;
    }
    public function getName()
    {
        return $this->name;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setVolume($volume)
    {
        $this->volume = $volume;
    }


}
