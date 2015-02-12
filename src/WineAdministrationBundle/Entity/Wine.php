<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wine
 *
 * @ORM\Table(name="Wine", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_Name_Vintage", columns={"name", "vintage", "wineType", "vinyard", "wineKind"})}, indexes={@ORM\Index(name="IDX_F63ECB5613D95DE5", columns={"vinyard"}), @ORM\Index(name="IDX_F63ECB56407143A", columns={"wineKind"}), @ORM\Index(name="IDX_F63ECB56B31DFFCA", columns={"wineType"})})
 * @ORM\Entity
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
     * @var \Vineyard
     *
     * @ORM\ManyToOne(targetEntity="Vineyard")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vinyard", referencedColumnName="id")
     * })
     */
    private $vinyard;

    /**
     * @var \Winekind
     *
     * @ORM\ManyToOne(targetEntity="Winekind")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineKind", referencedColumnName="id")
     * })
     */
    private $winekind;

    /**
     * @var \Winetype
     *
     * @ORM\ManyToOne(targetEntity="Winetype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineType", referencedColumnName="id")
     * })
     */
    private $winetype;

    public function __construct($available, $price, $name, \DateTime $vintage, $volume, \Vineyard $vinyard, \Winekind $winekind, \Winetype $winetype) {
        $this->setAvailable($available);
        $this->setPrice($price);
        $this->setName($name);
        $this->setVintage($vintage);
        $this->setVolume($volume);
        $this->setVinyard($vinyard);
        $this->setWinekind($winekind);
        $this->setWinetype($winetype);
    }

    public function getAvailable() {
        return $this->available;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getName() {
        return $this->name;
    }

    public function getVintage() {
        return $this->vintage;
    }

    public function getVolume() {
        return $this->volume;
    }

    public function getVinyard() {
        return $this->vinyard;
    }

    public function getWinekind() {
        return $this->winekind;
    }

    public function getWinetype() {
        return $this->winetype;
    }

    public function setAvailable($available) {
        $this->available = $available;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setVintage(\DateTime $vintage) {
        $this->vintage = $vintage;
    }

    public function setVolume($volume) {
        $this->volume = $volume;
    }

    public function setVinyard(\Vineyard $vinyard) {
        $this->vinyard = $vinyard;
    }

    public function setWinekind(\Winekind $winekind) {
        $this->winekind = $winekind;
    }

    public function setWinetype(\Winetype $winetype) {
        $this->winetype = $winetype;
    }
}
