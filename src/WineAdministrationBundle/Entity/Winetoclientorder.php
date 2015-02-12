<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winetoclientorder
 *
 * @ORM\Table(name="WineToClientOrder", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_Wine_Order", columns={"wine", "order"})}, indexes={@ORM\Index(name="IDX_26DCD1B4F5299398", columns={"order"}), @ORM\Index(name="IDX_26DCD1B4560C6468", columns={"wine"})})
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="Wine")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wine", referencedColumnName="id")
     * })
     */
    private $wine;

    /**
     * @var \Clientorder
     *
     * @ORM\ManyToOne(targetEntity="Clientorder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order", referencedColumnName="id")
     * })
     */
    private $order;

    public function __construct($amount, $price, \Clientorder $order, \Wine $wine) {
        $this->setAmount($amount);
        $this->setPrice($price);
        $this->setOrder($order);
        $this->setWine($wine);
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

    public function setOrder(\Clientorder $order) {
        $this->order = $order;
    }

    public function setWine(\Wine $wine) {
        $this->wine = $wine;
    }
}
