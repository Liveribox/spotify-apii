<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Playlist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


class UsuarioController extends AbstractController
{
    public function index()
    {
        return new Response('Hello!');
    }

    public function usuarios(SerializerInterface $serializer, Request $request){
        
        if ($request->isMethod("GET")){
            $usuarios=$this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findAll();
    
            $usuarios = $serializer->serialize(
                $usuarios,
                'json',
                ['groups' => ['usuario']]
            );
    
            return new Response($usuarios);
        }

        if ($request->isMethod("POST")) {
            $bodyData = $request->getContent();
            $usuario = $serializer->deserialize(
                $bodyData, Usuario::class,
                'json'
            );

            $this->getDoctrine()->getManager()->persist($usuario);
            $this->getDoctrine()->getManager()->flush();

            $usuario = $serializer->serialize(
                $usuario,
                "json",
                ["groups" => ["usuario"]]);

            return new Response($usuario);
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);

    }

    public function usuarioById(SerializerInterface $serializer, Request $request){
        $usuarioId = $request->get("id");
        $usuario = null;

        $usuario=$this->getDoctrine()->getRepository(Usuario::class)
        ->findOneBy(["id" => $usuarioId]);
        
        if ($request->isMethod("GET")){
            
    
            $usuarios = $serializer->serialize(
                $usuario,
                'json',
                ['groups' => ['usuario']]
            );
    
            return new Response($usuarios);
        }

        if($request->isMethod("PUT")){
            if (!empty($usuario)){
                $bodyData=$request->getContent();
                $usuario=$serializer->deserialize(
                $bodyData,
                Usuario::class,
                'json',
                ['object_to_populate'=>$usuario]
            ); 

            $this->getDoctrine()->getManager()->persist($usuario);
            $this->getDoctrine()->getManager()->flush();
            
            $usuario =$serializer->serialize(
                $usuario,
                'json',
                ['groups'=>['usuario']]

                );

                return new Response($usuario);
            }

            
            return new JsonResponse(["msg" => 'Affiliation not found, pringao'], 404);
        }

        if($request->isMethod("DELETE")){
            $deletedUsuario=clone $usuario;
            $this->getDoctrine()->getManager()->remove($usuario);
            $this->getDoctrine()->getManager()->flush();

            $deletedUsuario=$serializer->serialize($deletedUsuario,
            'json',
            ["groups" => ["usuario"]]);

            return new Response($deletedUsuario);
        }
        
    }

    public function playlistsByUsuario(SerializerInterface $serializer, Request $request){
        $usuarioId = $request->get("id");

        if ($request->isMethod("GET")){

            $playlists = $this->getDoctrine()
            ->getRepository(Playlist::class)
            ->findBy(["usuario" => $usuarioId]);

            $playlists = $serializer->serialize(
                $playlists,
                'json',
                ['groups' => ['playlist']]
            );

            return new Response($playlists);

        }
    }
     
}