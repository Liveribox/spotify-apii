<?php

namespace App\Controller;
use App\Entity\Podcast;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


class PodcastController extends AbstractController
{
    public function podcasts(SerializerInterface $serializer, Request $request){
        if ($request->isMethod("GET")){
            $podcasts=$this->getDoctrine()
            ->getRepository(Podcast::class)
            ->findAll();
    
            $podcasts = $serializer->serialize(
                $podcasts,
                'json',
                ['groups' => ['podcast']]
            );
    
            return new Response($podcasts);
        }

        if ($request->isMethod("POST")) {
            $bodyData = $request->getContent();
            $podcasts = $serializer->deserialize(
                $bodyData, Podcast::class,
                'json'
            );

            $this->getDoctrine()->getManager()->persist($podcasts);
            $this->getDoctrine()->getManager()->flush();

            $podcasts = $serializer->serialize(
                $podcasts,
                "json",
                ["groups" => ["affiliation"]]);

            return new Response($podcasts);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
        
    }

    public function podcastById(SerializerInterface $serializer, Request $request){

        $idPodcast=$request->get("id");
        $podcast=null;

        $podcast=$this->getDoctrine()
        ->getRepository(Podcast::class)
        ->findOneBy(["id"=>$idPodcast]);

        $podcastTitle=$podcast->getTitulo();
        $podcastDescri=$podcast->getDescripcion();


        if ($request->isMethod("GET")){

    
            $podcasts = $serializer->serialize(
                $podcast,
                'json',
                ['groups' => ['podcast']]
            );
    
            return new Response($podcasts);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }

    public function podcastsByUser(SerializerInterface $serializer, Request $request){

        $idUser=$request->get("id");
        $podcast=null;

        $usuario=$this->getDoctrine()
        ->getRepository(Usuario::class)
        ->findOneBy(["id"=>$idUser]);

        


        if ($request->isMethod("GET")){

    
            $podcasts = $serializer->serialize(
                $usuario,
                'json',
                ['groups' => ['podcast']]
            );
    
            return new Response($podcasts);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }
}
