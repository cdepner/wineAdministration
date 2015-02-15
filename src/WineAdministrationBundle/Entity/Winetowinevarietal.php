<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winetowinevarietal
 *
 * @ORM\Table(
 *     name="WineToWineVarietal",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_Wine_WineVarietal",
 *             columns={"wine", "wineVarietal"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="FK_WineToWineVarietal_WineVarietal",
 *             columns={"wineVarietal"}
 *         ),
 *         @ORM\Index(
 *             name="FK_WineToWineVarietal_Wine",
 *             columns={"wine"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="WinetowinevarietalRepository")
 */
class Winetowinevarietal
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
     * @var \Wine
     *
     * @ORM\ManyToOne(targetEntity="Wine", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wine", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $wine;

    /**
     * @var \Winevarietal
     *
     * @ORM\ManyToOne(targetEntity="Winevarietal", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineVarietal", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $winevarietal;

    public function __construct($wine, $winevarietal) {
        $this->setWine($wine);
        $this->setWinevarietal($winevarietal);
    }

    public function getId() {
        return $this->id;
    }

    public function getWine() {
        return $this->wine;
    }

    public function getWinevarietal() {
        return $this->winevarietal;
    }

    public function setWine($wine) {
        $this->wine = $wine;
    }

    public function setWinevarietal($winevarietal) {
        $this->winevarietal = $winevarietal;
    }
}
