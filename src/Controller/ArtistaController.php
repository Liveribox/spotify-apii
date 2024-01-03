<?php

namespace App\Controller;

use App\Entity\Artista;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ArtistaController extends AbstractController
{
    public function artistas(SerializerInterface $serializer, Request $request){
        if ($request->isMethod("GET")){
            $artistas=$this->getDoctrine()
            ->getRepository(Artista::class)
            ->findAll();
    
            $artistas = $serializer->serialize(
                $artistas,
                'json',
                ['groups' => ['artista']]
            );
    
            return new Response($artistas);
        }
    
    
        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }
}
