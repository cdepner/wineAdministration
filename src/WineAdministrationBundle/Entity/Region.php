<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="Region", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_name", columns={"name"})}, indexes={@ORM\Index(name="IDX_8CEF4405373C966", columns={"country"})})
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country", referencedColumnName="id")
     * })
     */
    private $country;

    public function __construct($name, \Country $country) {
        $this->setName($name);
        $this->setCountry($country);
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

    public function setCountry(\Country $country) {
        $this->country = $country;
    }
}
