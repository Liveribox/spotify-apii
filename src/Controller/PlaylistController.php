<?php

namespace App\Controller;

use App\Entity\Activa;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class PlaylistController extends AbstractController
{
    public function playlists(SerializerInterface $serializer , Request $request){
        if ($request->isMethod("GET")){

            $activa = $this->getDoctrine()->getRepository(Activa::class)
            ->findAll();

            $uplaylists=$this->getDoctrine()->getRepository(Playlist::class)
            ->findBy(["id" => $activa]);
    
            $uplaylists = $serializer->serialize(
                $uplaylists,
                'json',
                ['groups' => ['playlist']]
            );
    
            return new Response($uplaylists);
        }

    }

    public function playlistsById(SerializerInterface $serializer , Request $request){
        if ($request->isMethod("GET")){
            $playlistId = $request->get("id");

            $playlist = $this->getDoctrine()
            ->getRepository(Playlist::class)
            ->findOneBy(["id" => $playlistId]);

            $playlist = $serializer->serialize(
                $playlist,
                'json',
                ['groups' => ['playlist']]
            );
    
            return new Response($playlist);
        }

    }

    
    
}