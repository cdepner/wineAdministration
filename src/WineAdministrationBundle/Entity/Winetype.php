<?php
    /**
     * Wein Typ ORM Objekt
     * 
     * Schnittstelle zur Datenbank Tabelle Winetype
     *
     * @author C. Depner
     */
namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winetype
 *
 * @ORM\Table(
 *     name="WineType",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_name",
 *             columns={"name"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="WinetypeRepository")
 */
class Winetype
{
    /**
     * Wein Typ ID
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Name des Wein Typ
     * 
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * Konstruktor
     * 
     * @param $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * Gibt die ID eines Weintypen zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt den Namen eines Weintypen zurück
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setzt den Namen eines Weintypen
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
