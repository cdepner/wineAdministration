<?php

namespace WineAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Winetowinevarietal
 *
 * @ORM\Table(name="WineToWineVarietal", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_Wine_WineVarietal", columns={"wineId", "wineVarietalId"})}, indexes={@ORM\Index(name="FK_WineToWineVarietal_WineVarietal_idx", columns={"wineVarietalId"}), @ORM\Index(name="IDX_CE14AEF3CD465110", columns={"wineId"})})
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
     *   @ORM\JoinColumn(name="wineId", referencedColumnName="id")
     * })
     */
    private $wineid;

    /**
     * @var \Winevarietal
     *
     * @ORM\ManyToOne(targetEntity="Winevarietal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wineVarietalId", referencedColumnName="id")
     * })
     */
    private $winevarietalid;


}
