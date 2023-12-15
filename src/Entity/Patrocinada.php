<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Patrocinada
 *
 * @ORM\Table(name="patrocinada", indexes={@ORM\Index(name="fk_patrocinada_playlist1_idx", columns={"playlist_id"})})
 * @ORM\Entity
 */
class Patrocinada
{
    /**
     * @var bool
     *
     * @ORM\Column(name="patrocinada", type="boolean", nullable=false, options={"default"="1"})
     * @Groups("patrocinada")
     */
    private $patrocinada = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     * @Groups("patrocinada")
     */
    private $fechaInicio;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     * @Groups("patrocinada")
     */
    private $fechaFin;

    /**
     * @var Playlist
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Playlist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="playlist_id", referencedColumnName="id")
     * })
     */
    private $playlist;


}
