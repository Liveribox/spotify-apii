<?php

namespace App\Controller;

use App\Entity\Capitulo;
use App\Entity\Podcast;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CapituloController extends AbstractController{
    public function capitulosByPodcast(SerializerInterface $serializer, Request $request){

        $idPodcast=$request->get("id");

        
        $podcast=$this->getDoctrine()
            ->getRepository(Podcast::class)
            ->findOneBy(["id"=>$idPodcast]);

        $capitulos=$this->getDoctrine()
            ->getRepository(Capitulo::class)
            ->findBy(["podcast"=>$podcast]);
    
    
        if ($request->isMethod("GET")){
    
    
            $capitulos = $serializer->serialize(
                $capitulos,
                'json',
                ['groups' => ['capitulo']]
            );
    
            return new Response($capitulos);
        }
    
        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }


    public function capitulosById(SerializerInterface $serializer, Request $request){

        $idPodcast = $request->get("podcastId");
        $idCapitulo = $request->get("capituloId");

        $podcastRepository = $this->getDoctrine()->getRepository(Podcast::class);
        $capituloRepository = $this->getDoctrine()->getRepository(Capitulo::class);

        $podcast = $podcastRepository->find($idPodcast);
        $capitulo = $capituloRepository->find($idCapitulo);

        if (!$podcast || !$capitulo || $capitulo->getPodcast() !== $podcast) {
            return new JsonResponse(['error' => 'Parámetro no encontrado']);
        }

        if ($request->isMethod("GET")) {
            $data = $serializer->serialize(
                $capitulo,
                'json',
                ['groups' => ['capitulo']]
            );

            return new Response($data);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }
}

