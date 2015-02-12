<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="City", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_name_zipCode", columns={"name", "zipCode"})})
 * @ORM\Entity
 */
class City
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
     * @var integer
     *
     * @ORM\Column(name="zipCode", type="integer", nullable=false)
     */
    private $zipcode;
    
    
    public function __construct($name, $zipcode) {
        $this->setName($name);
        $this->setZipcode($zipcode);
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getZipcode() {
        return $this->zipcode;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setZipcode($zipcode) {
        $this->zipcode = $zipcode;
    }
}
