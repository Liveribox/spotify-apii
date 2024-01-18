<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Suscripcion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;


class SuscripcionController extends AbstractController{

    public function suscripciones(SerializerInterface $serializer, Request $request){

        if($request->isMethod("GET")){
            $usuarioId = $request->get("id");

            $usuario = $this->getDoctrine()->getRepository(Usuario::class)
            ->findOneBy([ "id" => $usuarioId]);

            $suscripcion = $this->getDoctrine()->getRepository(Suscripcion::class)
            ->findBy(["premiumUsuario" => $usuario]);

            $suscripcion = $serializer->serialize(
                $suscripcion,
                'json',
                ["groups" => ["suscripcion"]]
            );

            return new Response($suscripcion);
        }
        
    }

    public function suscripcionesById(SerializerInterface $serializer, Request $request){
        

        if($request->isMethod("GET")){
            $usuarioId = $request->get("usuarioId");
            $suscripcionId = $request->get("suscripcionId");


            $suscripcion = $this->getDoctrine()->getRepository(Suscripcion::class)
            ->findOneBy(["id" => $suscripcionId , "premiumUsuario" => $usuarioId]);

            $suscripcion = $serializer->serialize(
                $suscripcion,
                'json',
                ["groups" => ["suscripcion"]]
            );

            return new Response($suscripcion);


        }
    }

}