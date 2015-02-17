<?php

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
     * @param $name
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
