<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wine
 *
 * @ORM\Table(name="Wine", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_Name_Vintage", columns={"name", "vintage", "wineTypeId", "vinyardId", "wineKindId"})}, indexes={@ORM\Index(name="FK_Wine_WineType_idx", columns={"wineTypeId"}), @ORM\Index(name="FK_Wine_Vineyard_idx", columns={"vinyardId"}), @ORM\Index(name="FK_Wine_WineKind_idx", columns={"wineKindId"})})
 * @ORM\Entity
 */
class Wine
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
     * @var boolean
     *
     * @ORM\Column(name="available", type="boolean", nullable=false)
     */
    private $available;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vintage", type="date", nullable=false)
     */
    private $vintage;

    /**
     * @var float
     *
     * @ORM\Column(name="volume", type="float", precision=10, scale=0, nullable=false)
     */
    private $volume;

    /**
     * @var \Vineyard
     *
     * @ORM\ManyToOne(targetEntity="Vineyard")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vinyardId", referencedColumnName="id")
     * })
     */
    private $vinyardid;

    /**
     * @var \Winekind
     *
     * @ORM\ManyToOne(targetEntity="Winekind")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineKindId", referencedColumnName="id")
     * })
     */
    private $winekindid;

    /**
     * @var \Winetype
     *
     * @ORM\ManyToOne(targetEntity="Winetype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineTypeId", referencedColumnName="id")
     * })
     */
    private $winetypeid;


}
