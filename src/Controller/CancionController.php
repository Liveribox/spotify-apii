<?php

namespace App\Controller;

use App\Entity\Cancion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CancionController extends AbstractController
{
    public function canciones(SerializerInterface $serializer, Request $request){
        if ($request->isMethod("GET")){
            $canciones=$this->getDoctrine()
            ->getRepository(Cancion::class)
            ->findAll();
    
            $canciones = $serializer->serialize(
                $canciones,
                'json',
                ['groups' => ['cancion']]
            );
    
            return new Response($canciones);
        }
    
    
        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }

    public function cancionesById(SerializerInterface $serializer, Request $request){

        $idCancion=$request->get("id");
        $cancion=null;

        $cancion=$this->getDoctrine()
        ->getRepository(Cancion::class)
        ->findOneBy(["id"=>$idCancion]);



        if ($request->isMethod("GET")){

    
            $canciones = $serializer->serialize(
                $cancion,
                'json',
                ['groups' => ['cancion']]
            );
    
            return new Response($canciones);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }


}
