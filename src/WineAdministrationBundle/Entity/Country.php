<?php
    /**
     * Land ORM Objekt
     * 
     * Schnittstelle zur Datenbank Tabelle Country
     *
     * @author C. Depner
     */
namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(
 *     name="Country",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_name",
 *             columns={"name"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="CountryRepository")
 */
class Country
{
    /**
     * Land ID
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Name des Landes
     * 
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * Konstruktor
     * 
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * Gibt die ID eines Landes zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setzt den Namen eines Landes
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setzt den Namen eines Landes
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
