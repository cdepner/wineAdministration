<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winetoclientorder
 *
 * @ORM\Table(name="WineToClientOrder", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_Wine_Order", columns={"wineId", "orderId"})}, indexes={@ORM\Index(name="FK_WineToClientOrder_Wine_idx", columns={"wineId"}), @ORM\Index(name="FK_WineToClientOrder_idx", columns={"orderId"})})
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
     * @var \Clientorder
     *
     * @ORM\ManyToOne(targetEntity="Clientorder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="orderId", referencedColumnName="id")
     * })
     */
    private $orderid;

    /**
     * @var \Wine
     *
     * @ORM\ManyToOne(targetEntity="Wine")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineId", referencedColumnName="id")
     * })
     */
    private $wineid;


}
