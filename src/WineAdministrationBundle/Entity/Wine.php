<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wine
 *
 * @ORM\Table(
 *     name="Wine",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="UNIQUE_Name_Vintage",
 *             columns={
 *                 "name",
 *                 "vintage",
 *                 "wineType",
 *                 "vinyard",
 *                 "wineKind"
 *             }
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="FK_Wine_Vineyard",
 *             columns={"vinyard"}
 *         ),
 *         @ORM\Index(
 *             name="FK_Wine_Winekind",
 *             columns={"wineKind"}
 *         ),
 *         @ORM\Index(
 *             name="FK_Wine_Winetype",
 *             columns={"wineType"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="WineRepository")
 */
class Wine
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
     * @var boolean
     *
     * @ORM\Column(name="available", type="boolean", nullable=false)
     */
    private $available;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vintage", type="date", nullable=false)
     */
    private $vintage;

    /**
     * @var float
     *
     * @ORM\Column(name="volume", type="float", precision=10, scale=0, nullable=false)
     */
    private $volume;

    /**
     * @var Vineyard
     *
     * @ORM\ManyToOne(targetEntity="Vineyard", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vinyard", referencedColumnName="id", nullable=false, onDelete="NO ACTION")
     * })
     */
    private $vineyard;

    /**
     * @var Winekind
     *
     * @ORM\ManyToOne(targetEntity="Winekind", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineKind", referencedColumnName="id", nullable=false, onDelete="NO ACTION")
     * })
     */
    private $winekind;

    /**
     * @var Winetype
     *
     * @ORM\ManyToOne(targetEntity="Winetype", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineType", referencedColumnName="id", nullable=false, onDelete="NO ACTION")
     * })
     */
    private $winetype;

    /**
     * @param bool      $available
     * @param int       $price
     * @param string    $name
     * @param \DateTime $vintage
     * @param float     $volume
     * @param Vineyard  $vinyard
     * @param Winekind  $winekind
     * @param Winetype  $winetype
     */
    public function __construct($available, $price, $name, \DateTime $vintage, $volume, Vineyard $vinyard, Winekind $winekind, Winetype $winetype)
    {
        $this->setAvailable($available);
        $this->setPrice($price);
        $this->setName($name);
        $this->setVintage($vintage);
        $this->setVolume($volume);
        $this->setVineyard($vinyard);
        $this->setWinekind($winekind);
        $this->setWinetype($winetype);
    }

    /**
     * Gibt die ID eines Weines zurück
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gibt die Verfügbarkeit eines Weines zurück
     *
     * @return bool
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Setzt die Verfügbarkeit eines Weines
     *
     * @param bool $available
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    }

    /**
     * Gibt den Preis eines Weines zurück
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Setzt den Preis eines Weines
     *
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Gibt den Names eines Weine zurück
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setzt den Namen eines Weines
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gibt das Altes eines Weines zurück
     *
     * @return \DateTime
     */
    public function getVintage()
    {
        return $this->vintage;
    }

    /**
     * Setzt das Alter eines Weines
     *
     * @param \DateTime $vintage
     */
    public function setVintage(\DateTime $vintage)
    {
        $this->vintage = $vintage;
    }

    /**
     * Gibt die Füllmenge eines Weines zurück
     *
     * @return float
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Setzt die Füllmenge eines Weines
     *
     * @param float $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * Gibt das Weingut eines Weines zurück
     *
     * @return Vineyard
     */
    public function getVineyard()
    {
        return $this->vineyard;
    }

    /**
     * Setzt das Weingut eines Weines
     *
     * @param Vineyard $vineyard
     */
    public function setVineyard(Vineyard $vineyard)
    {
        $this->vineyard = $vineyard;
    }

    /**
     * Gibt die Art eines Weines zurück
     *
     * @return Winekind
     */
    public function getWinekind()
    {
        return $this->winekind;
    }

    /**
     * Setzt die Art eines Weines
     *
     * @param Winekind $winekind
     */
    public function setWinekind(Winekind $winekind)
    {
        $this->winekind = $winekind;
    }

    /**
     * Gibt den Typ eines Weines zurück
     *
     * @return Winetype
     */
    public function getWinetype()
    {
        return $this->winetype;
    }

    /**
     * Setzt den Typ eines Weines
     *
     * @param Winetype $winetype
     */
    public function setWinetype(Winetype $winetype)
    {
        $this->winetype = $winetype;
    }
}
