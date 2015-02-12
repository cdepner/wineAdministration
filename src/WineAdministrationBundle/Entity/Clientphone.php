<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientphone
 *
 * @ORM\Table(name="ClientPhone", uniqueConstraints={@ORM\UniqueConstraint(name="number_UNIQUE", columns={"number"})}, indexes={@ORM\Index(name="FK_ClientPhone_Client_idx", columns={"clientId"})})
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
     *   @ORM\JoinColumn(name="clientId", referencedColumnName="id")
     * })
     */
    private $clientid;


}
