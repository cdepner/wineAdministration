<?php
    /**
     * Weinrebsorte ORM Objekt
     * 
     * Schnittstelle zur Datenbank Tabelle Winevarietal
     *
     * @author C. Depner
     */
namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winevarietal
 *
 * @ORM\Table(
 *     name="WineVarietal",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_name",
 *             columns={"name"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="WinevarietalRepository")
 */
class Winevarietal
{
    /**
     * Weinrebsorte ID
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Name der Weinrebsorte
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
     * Gibt die ID einer Rebsorte zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt den Namen einer Rebsorte zurück
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setzt den Namen einer Rebsorte
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
