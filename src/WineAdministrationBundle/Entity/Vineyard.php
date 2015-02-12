<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vineyard
 *
 * @ORM\Table(name="Vineyard", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_name", columns={"name"})}, indexes={@ORM\Index(name="FK_Vineyard_City_idx", columns={"cityId"}), @ORM\Index(name="FK_Vineyard_Region_idx", columns={"regionId"})})
 * @ORM\Entity
 */
class Vineyard
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
     * @var \City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cityId", referencedColumnName="id")
     * })
     */
    private $cityid;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="regionId", referencedColumnName="id")
     * })
     */
    private $regionid;


}
