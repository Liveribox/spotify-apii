<?php

namespace App\Controller;

use App\Entity\AnyadeCancionPlaylist;
use App\Entity\Cancion;
use App\Entity\Playlist;
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

    public function cancionesByPlaylist(SerializerInterface $serializer, Request $request){

        $idPlaylist = $request->get("id");

        $playlist = $this->getDoctrine()
            ->getRepository(Playlist::class)
            ->findOneBy(["id" => $idPlaylist]);

        if (!$playlist) {
            return new JsonResponse(["msg" => "Playlist no encontrada"]);
        }

        $cancionesPlaylist = $this->getDoctrine()
            ->getRepository(AnyadeCancionPlaylist::class)
            ->findBy(["playlist" => $playlist]);

        if (!$cancionesPlaylist) {
            return new JsonResponse(["msg" => "La playlist no contiene canciones"]);
        }

        $canciones = [];
        foreach ($cancionesPlaylist as $cancionPlaylist) {
            $canciones[] = $cancionPlaylist->getCancion();
        }

        if ($request->isMethod("GET")) {
            $canciones = $serializer->serialize(
                $canciones,
                'json',
                ['groups' => ['cancion']]
            );

            return new Response($canciones);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }

    public function modiCancionesById(SerializerInterface $serializer, Request $request){

        if ($request->isMethod("POST")) {
            $bodyData = $request->getContent();
            $canciones = $serializer->deserialize(
                $bodyData, Cancion::class,
                'json'
            );

            $this->getDoctrine()->getManager()->persist($canciones);
            $this->getDoctrine()->getManager()->flush();

            $canciones = $serializer->serialize(
                $canciones,
                "json",
                ["groups" => ["affiliation"]]);

            return new Response($canciones);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
        
    }


}
