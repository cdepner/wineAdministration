<?php
    /**
     * Wein Art ORM Objekt
     * 
     * Schnittstelle zur Datenbank Tabelle Weinkind
     *
     * @author C. Depner
     */
namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winekind
 *
 * @ORM\Table(
 *     name="WineKind",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_name",
 *             columns={"name"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="WinekindRepository")
 */
class Winekind
{
    /**
     * Wein Art ID
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Name der Wein Art
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
     * Gibt die ID einer Weinart zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt den Namen einer Weinart zurück
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setzt den Namen einer Weinart
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
