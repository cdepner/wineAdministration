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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $country;

    /**
     * @param string  $name
     * @param Country $country
     */
    public function __construct($name, Country $country)
    {
        $this->setName($name);
        $this->setCountry($country);
    }

    /**
     * Gibt die ID einer Region zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt den Namen einer Region zurück
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setzt den Namen einer Region
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gibt das Land einer Region zurück
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Setzt das Land einer Region
     *
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }
}
