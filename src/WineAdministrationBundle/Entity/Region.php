<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(
 *     name="Region",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_name",
 *             columns={"name"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="FK_Region_Country",
 *             columns={"country"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="RegionRepository")
 */
class Region
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
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $country;

    public function __construct($name, $country) {
        $this->setName($name);
        $this->setCountry($country);
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCountry($country) {
        $this->country = $country;
    }
}
