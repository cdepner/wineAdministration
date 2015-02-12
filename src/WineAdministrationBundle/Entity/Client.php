<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="Client", uniqueConstraints={@ORM\UniqueConstraint(name="UINQUE_Name_Street_City", columns={"forename", "surname", "street", "streetNo", "cityId"})}, indexes={@ORM\Index(name="FK_Client_City_idx", columns={"cityId"})})
 * @ORM\Entity
 */
class Client
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
     * @ORM\Column(name="forename", type="string", length=45, nullable=false)
     */
    private $forename;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=false)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=45, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="streetNo", type="string", length=9, nullable=false)
     */
    private $streetno;

    /**
     * @var \City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cityId", referencedColumnName="id")
     * })
     */
    private $cityid;


}
