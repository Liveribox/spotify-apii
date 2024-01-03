<?php

namespace App\Controller;

use App\Entity\TipoDescarga;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


class TipoDescargaController extends AbstractController
{
    public function tiposDescargas(SerializerInterface $serializer, Request $request){
        if ($request->isMethod("GET")){
            $tiposdescargas=$this->getDoctrine()
            ->getRepository(TipoDescarga::class)
            ->findAll();
    
            $tiposdescargas = $serializer->serialize(
                $tiposdescargas,
                'json',
                ['groups' => ['tipoDescarga']]
            );
    
            return new Response($tiposdescargas);
        }
    
    
        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }
}