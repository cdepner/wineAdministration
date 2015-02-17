<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vineyard
 *
 * @ORM\Table(
 *     name="Vineyard",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_name",
 *             columns={"name"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="FK_Vineyard_City",
 *             columns={"city"}
 *         ),
 *         @ORM\Index(
 *             name="FK_Vineyard_Region",
 *             columns={"region"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="VineyardRepository")
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
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $city;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $region;

    /**
     * @param string $name
     * @param City   $city
     * @param Region $region
     */
    public function __construct($name, City $city, Region $region)
    {
        $this->setName($name);
        $this->setCity($city);
        $this->setRegion($region);
    }

    /**
     * Gibt die ID eines Weingutes zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt den Namen eines Weingutes zurück
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setzt den Namen eines Weingutes
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gibt die Stadt eines Weingutes zurück
     *
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Setzt die Stadt eines Weingutes
     *
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }

    /**
     * Gibt die Region eines Weingutes zurück
     *
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Setzt die Region eines Weingutes
     *
     * @param Region $region
     */
    public function setRegion(Region $region)
    {
        $this->region = $region;
    }
}
