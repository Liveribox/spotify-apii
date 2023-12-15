<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Letra
 *
 * @ORM\Table(name="letra", uniqueConstraints={@ORM\UniqueConstraint(name="cancion_id_UNIQUE", columns={"cancion_id"})})
 * @ORM\Entity
 */
class Letra
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("letra")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=255, nullable=false)
     * @Groups("letra")
     */
    private $ruta;

    /**
     * @var Cancion
     *
     * @ORM\ManyToOne(targetEntity="Cancion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cancion_id", referencedColumnName="id")
     * })
     */
    private $cancion;


}
