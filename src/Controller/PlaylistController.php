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

        if ($request->isMethod("POST")){
            $playlist = new Playlist();

            if(!empty($playlist)){
                $bodyData = $request->getContent();
                $playlist = $serializer->deserialize(
                $bodyData,
                Playlist::class,
                'json'
                
            );
            }

            $playlist->setFechaCreacion(new \DateTime());

            $usuario = $this->getDoctrine()->getRepository(Usuario::class)
            ->findOneBy(["id"=>["6"]]);

            $playlist->setUsuario($usuario);

            
            
            $this->getDoctrine()->getManager()->persist($playlist);
            $this->getDoctrine()->getManager()->flush();

            $activa = new Activa();

            
            $activa->setEsCompartida(true);
            $activa->setPlaylist($playlist);
            

            $this->getDoctrine()->getManager()->persist($activa);
            $this->getDoctrine()->getManager()->flush();

            $playlist = $serializer->serialize(
                $playlist,
                "json",
                ["groups" => ["playlist"]]
            );

            return new Response($playlist);
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