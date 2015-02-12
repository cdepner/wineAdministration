<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientorder
 *
 * @ORM\Table(name="ClientOrder", indexes={@ORM\Index(name="FK_ClientOrder_Client_idx", columns={"clientId"})})
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
     *   @ORM\JoinColumn(name="clientId", referencedColumnName="id")
     * })
     */
    private $clientid;


}
