<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vineyard
 *
 * @ORM\Table(name="Vineyard", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_name", columns={"name"})}, indexes={@ORM\Index(name="IDX_839D4A3D2D5B0234", columns={"city"}), @ORM\Index(name="IDX_839D4A3DF62F176", columns={"region"})})
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
     *   @ORM\JoinColumn(name="city", referencedColumnName="id")
     * })
     */
    private $city;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region", referencedColumnName="id")
     * })
     */
    private $region;

    public function __construct($name, \City $city, \Region $region) {
        $this->setName($name);
        $this->setCity($city);
        $this->setRegion($region);
    }

    public function getName() {
        return $this->name;
    }

    public function getCity() {
        return $this->city;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCity(\City $city) {
        $this->city = $city;
    }

    public function setRegion(\Region $region) {
        $this->region = $region;
    }
}
