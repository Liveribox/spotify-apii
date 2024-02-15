<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Cancion;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AlbumController extends AbstractController
{
    public function albums(SerializerInterface $serializer, Request $request){
        if($request->isMethod("GET")){
            $albumes = $this->getDoctrine()
            ->getRepository(Album::class)
            ->findAll();

            $albumes = $serializer->serialize(
                $albumes,
                'json',
                ['groups' => ['album','usuario']]
            );

            return new Response($albumes);
        }

        
    }

    public function albumsById(SerializerInterface $serializer, Request $request){
        $albumId = $request->get("id");


        if($request->isMethod("GET")){
            $album = $this->getDoctrine()
            ->getRepository(Album::class)
            ->findOneBy(["id" => $albumId]);

            $album = $serializer->serialize(
                $album,
                'json',
                ["groups" => ["album","usuario"]]
            );

            return new Response($album);
        }
    }

    public function albumsAndCanciones(SerializerInterface $serializer, Request $request){
        $albumId = $request->get("id");

        if($request->isMethod("GET")){
            $canciones = $this->getDoctrine()
            ->getRepository(Cancion::class)
            ->findBy(["album" => $albumId]);

            $canciones = $serializer->serialize(
                $canciones,
                'json',
                ["groups" => ["cancion"]]
            );

            return new Response($canciones);
        }
    }


    public function albumByUsuario(SerializerInterface $serializer, Request $request){
        $usuarioId = $request->get("id");

        $usuario = $this->getDoctrine()->getRepository(Usuario::class)
        ->findOneBy(["id" => $usuarioId]);

        $albums = $usuario->getAlbum();

        if($request->isMethod("GET")){

            $albums = $serializer->serialize(
                $albums,
                "json",
                ["groups" => ["album"]]
            );
    
            return new Response($albums);

        }

        
        

    }


    
}