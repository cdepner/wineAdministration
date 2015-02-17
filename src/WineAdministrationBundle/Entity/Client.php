<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(
 *     name="Client",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UINQUE_Name_Street_City",
 *             columns={
 *                 "forename",
 *                 "surname",
 *                 "street",
 *                 "streetNo",
 *                 "city"
 *             }
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="FK_Client_City",
 *             columns={"city"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="ClientRepository")
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
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false, onDelete="NO ACTION")
     * })
     */
    private $city;

    /**
     * @param string $forename
     * @param string $surname
     * @param string $street
     * @param string $streetno
     * @param City   $city
     */
    public function __construct($forename, $surname, $street, $streetno, City $city)
    {
        $this->setForename($forename);
        $this->setSurname($surname);
        $this->setStreet($street);
        $this->setStreetno($streetno);
        $this->setCity($city);
    }

    /**
     * Gibt die ID eines Kunden zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt den Vornamen eines Kunden zurück
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * Setzt den Vornamen eines Kunden
     *
     * @param string $forename
     */
    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    /**
     * Gibt den Nachnamen eines Kunden zurück
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Setzt den Nachnamen eines Kunden
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Gibt die Straße eines Kunden zurück
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Setzt die Straße eines Kunden
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * Gibt die Straßennummer eines Kunden zurück
     *
     * @return string
     */
    public function getStreetno()
    {
        return $this->streetno;
    }

    /**
     * Setzt die Straßennummer eines Kunden
     *
     * @param string $streetno
     */
    public function setStreetno($streetno)
    {
        $this->streetno = $streetno;
    }

    /**
     * Gibt die Stadt eines Kunden zurück
     *
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Setzt die Stadt eines Kunden
     *
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }
}
