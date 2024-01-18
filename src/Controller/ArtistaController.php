<?php

namespace App\Controller;

use App\Entity\Artista;
use App\Entity\Album;
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


    public function artistasAlbums(SerializerInterface $serializer, Request $request){

        $idArtista=$request->get("id");

        $artista=$this->getDoctrine()
        ->getRepository(Artista::class)
        ->findOneBy(["id"=>$idArtista]);

        $albums=$this->getDoctrine()
        ->getRepository(Album::class)
        ->findBy(["artista"=>$artista]);

        if ($request->isMethod("GET")){
    
            $albums = $serializer->serialize(
                $albums,
                'json',
                ['groups' => ['album']]
            );
    
            return new Response($albums);
        }
    
    
        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }

    public function artistasAlbumsByIds(SerializerInterface $serializer, Request $request){

        $idArtista = $request->get("artistaId");
        $idAlbum = $request->get("albumId");

        $artistaRepository = $this->getDoctrine()->getRepository(Artista::class);
        $albumRepository = $this->getDoctrine()->getRepository(Album::class);

        $artista = $artistaRepository->find($idArtista);
        $album = $albumRepository->find($idAlbum);

        if (!$artista || !$album || $album->getArtista() !== $artista) {
            return new JsonResponse(['error' => 'Parámetro no encontrado']);
        }

        if ($request->isMethod("GET")) {
            $data = $serializer->serialize(
                $album,
                'json',
                ['groups' => ['album']]
            );

            return new Response($data);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);
    }
}
