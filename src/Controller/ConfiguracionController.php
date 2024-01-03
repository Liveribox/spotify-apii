<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Configuracion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ConfiguracionController extends AbstractController
{
    public function configuracionByUsuario(SerializerInterface $serializer, Request $request){
        $usuarioId = $request->get("usuario_id");

        if ($request->isMethod("GET")){

            $configuraciones = $this->getDoctrine()
            ->getRepository(Configuracion::class)
            ->findBy(["usuario" => $usuarioId]);

            $configuraciones = $serializer->serialize(
                $configuraciones,
                'json',
                ["groups" => ["configuracion"]] 
            );

            return new Response($configuraciones);
        }
    }
}