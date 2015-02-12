<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winetowinevarietal
 *
 * @ORM\Table(name="WineToWineVarietal", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_Wine_WineVarietal", columns={"wine", "wineVarietal"})}, indexes={@ORM\Index(name="FK_WineToWineVarietal_WineVarietal_idx", columns={"wineVarietal"}), @ORM\Index(name="IDX_CE14AEF3560C6468", columns={"wine"})})
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="Wine")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wine", referencedColumnName="id")
     * })
     */
    private $wine;

    /**
     * @var \Winevarietal
     *
     * @ORM\ManyToOne(targetEntity="Winevarietal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineVarietal", referencedColumnName="id")
     * })
     */
    private $winevarietal;

    public function __construct(\Wine $wine, \Winevarietal $winevarietal) {
        $this->setWine($wine);
        $this->setWinevarietal($winevarietal);
    }

    public function getWine() {
        return $this->wine;
    }

    public function getWinevarietal() {
        return $this->winevarietal;
    }

    public function setWine(\Wine $wine) {
        $this->wine = $wine;
    }

    public function setWinevarietal(\Winevarietal $winevarietal) {
        $this->winevarietal = $winevarietal;
    }
}
