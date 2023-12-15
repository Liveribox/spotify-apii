<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Free
 *
 * @ORM\Table(name="free", indexes={@ORM\Index(name="fecha_revision_idx", columns={"fecha_revision"}), @ORM\Index(name="fk_free_usuario1_idx", columns={"usuario_id"})})
 * @ORM\Entity
 */
class Free
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_revision", type="date", nullable=false)
     * @Groups("free")
     */
    private $fechaRevision;

    /**
     * @var int
     *
     * @ORM\Column(name="tiempo_publicidad", type="integer", nullable=false, options={"default"="600"})
     * @Groups("free")
     */
    private $tiempoPublicidad = 600;

    /**
     * @var Usuario
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;


}
