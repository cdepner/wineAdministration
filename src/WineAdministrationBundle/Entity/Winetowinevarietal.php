<?php
    /**
     * Wein zu Weinrebsorte ORM Objekt
     * 
     * Schnittstelle zur Datenbank Tabelle Winetowinevarietal
     *
     * @author C. Depner
     */
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
     * Wein zu Weinrebstorte ID
     * 
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Wein
     * 
     * @var Wine
     *
     * @ORM\ManyToOne(targetEntity="Wine", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wine", referencedColumnName="id", nullable=false)
     * })
     */
    private $wine;

    /**
     * Weinrebsorte
     * 
     * @var Winevarietal
     *
     * @ORM\ManyToOne(targetEntity="Winevarietal", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineVarietal", referencedColumnName="id", nullable=false)
     * })
     */
    private $winevarietal;

    /**
     * Konstruktor
     * 
     * @param Wine         $wine
     * @param Winevarietal $winevarietal
     */
    public function __construct(Wine $wine, Winevarietal $winevarietal)
    {
        $this->setWine($wine);
        $this->setWinevarietal($winevarietal);
    }

    /**
     * Gibt die ID einer Wein-zu-Rebsorte zurück
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt den Wein einer Wein-zu-Rebsorte zurück
     *
     * @return Wine
     */
    public function getWine()
    {
        return $this->wine;
    }

    /**
     * Setzt den Wein einer Wein-zu-Rebsorte
     *
     * @param Wine $wine
     */
    public function setWine(Wine $wine)
    {
        $this->wine = $wine;
    }

    /**
     * Gibt die Rebsorte einer Wein-zu-Rebsorte zurück
     *
     * @return Winevarietal
     */
    public function getWinevarietal()
    {
        return $this->winevarietal;
    }

    /**
     * Setzt die Rebsorte einer Wein-zu-Rebsorte
     *
     * @param Winevarietal $winevarietal
     */
    public function setWinevarietal(Winevarietal $winevarietal)
    {
        $this->winevarietal = $winevarietal;
    }
}
