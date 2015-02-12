<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="Client", uniqueConstraints={@ORM\UniqueConstraint(name="UINQUE_Name_Street_City", columns={"forename", "surname", "street", "streetNo", "city"})}, indexes={@ORM\Index(name="FK_Client_City_idx", columns={"city"})})
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
     *   @ORM\JoinColumn(name="city", referencedColumnName="id")
     * })
     */
    private $city;

    public function __construct($forename, $surname, $street, $streetno, \City $city) {
        $this->setForename($forename);
        $this->setSurname($surname);
        $this->setStreet($street);
        $this->setStreetno($streetno);
        $this->setCity($city);
    }

    public function getForename() {
        return $this->forename;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getStreetno() {
        return $this->streetno;
    }

    public function getCity() {
        return $this->city;
    }

    public function setForename($forename) {
        $this->forename = $forename;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function setStreet($street) {
        $this->street = $street;
    }

    public function setStreetno($streetno) {
        $this->streetno = $streetno;
    }

    public function setCity(\City $city) {
        $this->city = $city;
    }
}
