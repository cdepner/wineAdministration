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
     * @var \City
     *
     * @ORM\ManyToOne(targetEntity="City", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $city;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $region;

    public function __construct($name, $city, $region) {
        $this->setName($name);
        $this->setCity($city);
        $this->setRegion($region);
    }

    public function getId() {
        return $this->id;
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

    public function setCity($city) {
        $this->city = $city;
    }

    public function setRegion($region) {
        $this->region = $region;
    }
}
