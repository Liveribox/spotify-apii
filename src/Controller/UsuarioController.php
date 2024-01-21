<?php

namespace App\Controller;

use App\Entity\AnyadeCancionPlaylist;
use App\Entity\Eliminada;
use App\Entity\Usuario;
use App\Entity\Playlist;
use App\Entity\Free;
use App\Entity\Configuracion;
use App\Entity\Calidad;
use App\Entity\Idioma;
use App\Entity\Premium;
use App\Entity\TipoDescarga;
use App\Entity\Favoritas;
use App\Entity\Suscripcion;
use App\Entity\Activa;
use App\Entity\Patrocinada;
use App\Entity\Pago;

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
            
            $usuario = new Usuario();
            if(!empty($usuario)){
                $bodyData = $request->getContent();
                $usuario = $serializer->deserialize(
                $bodyData,
                Usuario::class,
                'json'
                
            );
            }
            
            $this->getDoctrine()->getManager()->persist($usuario);
            $this->getDoctrine()->getManager()->flush();

            

            $op = 'premium';
            

            if($op == 'free'){
                $free = new Free();
                $free->setFechaRevision(new \DateTime());
                $free->setTiempoPublicidad(0);
                $free->setUsuario($usuario);

                $this->getDoctrine()->getManager()->persist($free);
                $this->getDoctrine()->getManager()->flush();
            }
            else if($op == 'premium'){
                $premium = new Premium();
                $premium->setFechaRenovacion(new \DateTime());
                $premium->setUsuario($usuario);

                $this->getDoctrine()->getManager()->persist($premium);
                $this->getDoctrine()->getManager()->flush();
            }
            

            $calidad = $this->getDoctrine()->getRepository(Calidad::class)
            ->findOneBy(["nombre"=>["Normal"]]);
            
            $idioma = $this->getDoctrine()->getRepository(Idioma::class)
            ->findOneBy(["nombre"=>["Italiano"]]);

            $tipoDescarga = $this->getDoctrine()->getRepository(TipoDescarga::class)
            ->findOneBy(["nombre"=>["Alta"]]);


            $configuracion = new Configuracion();
            $configuracion->setAutoplay(true);
            $configuracion->setAjuste("0");
            $configuracion->setNormalizacion(true);
            $configuracion->setUsuario($usuario);
            $configuracion->setCalidad($calidad);
            $configuracion->setIdioma($idioma);
            $configuracion->setTipoDescarga($tipoDescarga);

            $this->getDoctrine()->getManager()->persist($configuracion);
            $this->getDoctrine()->getManager()->flush();

            $usuario = $serializer->serialize(
                $usuario,
                "json",
                ["groups" => ["usuario"]]
            );

            return new Response($usuario);

            
            
        }

        return new JsonResponse(["msg" => $request->getMethod() . " not allowed"]);

    }

    public function usuarioById(SerializerInterface $serializer, Request $request){
        
        $usuarioId = $request->get("id");
        
        $usuario=$this->getDoctrine()->getRepository(Usuario::class)
            ->findOneBy(["id" => $usuarioId]);

        $freeUsuario = $this->getDoctrine()->getRepository(Free::class)
            ->findBy(["usuario" => $usuario]);

        $premiumUsuario = $this->getDoctrine()->getRepository(Premium::class)
            ->findBy(["usuario"=> $usuario]);

        $suscripcionUsuario = $this->getDoctrine()->getRepository(Suscripcion::class)
            ->findBy(["premiumUsuario"=> $usuario]);

        $configUsuario = $this->getDoctrine()->getRepository(Configuracion::class)
            ->findBy(["usuario" => $usuario]);

        $playlistUsuario = $this->getDoctrine()->getRepository(Playlist::class)
            ->findBy(["usuario" => $usuario]);


        $anyadeCancionPlaylistUsuario = $this->getDoctrine()->getRepository(AnyadeCancionPlaylist::class)
            ->findBy(["usuario" => $usuario]);

        $anyadeCancionPlaylistPlaylist = $this->getDoctrine()->getRepository(AnyadeCancionPlaylist::class)
            ->findBy(["playlist" => $playlistUsuario]);

        $favUsuario = $this->getDoctrine()->getRepository(Favoritas::class)
            ->findBy(["playlist"=> $playlistUsuario]);

        $activaUsuario = $this->getDoctrine()->getRepository(Activa::class)
            ->findBy(["playlist"=> $playlistUsuario]);
        
        $patrocinadaUsuario = $this->getDoctrine()->getRepository(Patrocinada::class)
            ->findBy(["playlist"=> $playlistUsuario]);

        $pagoUsuario = $this->getDoctrine()->getRepository(Pago::class)
            ->findBy(["suscripcion"=> $suscripcionUsuario]);

        
        
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

            
            return new JsonResponse(["msg" => 'Affiliation not found'], 404);
        }

        if($request->isMethod("DELETE")){ 
            $deletedUsuario=clone $usuario;

            foreach($pagoUsuario as $pago){
                $this->getDoctrine()->getManager()->remove($pago);
                $this->getDoctrine()->getManager()->flush();
            }

            foreach($suscripcionUsuario as $suscripcion){
                $this->getDoctrine()->getManager()->remove($suscripcion);
                $this->getDoctrine()->getManager()->flush();
            }

            foreach($patrocinadaUsuario as $pat){
                $this->getDoctrine()->getManager()->remove($pat);
                $this->getDoctrine()->getManager()->flush();  
            }

            foreach($activaUsuario as $activa){
                $this->getDoctrine()->getManager()->remove($activa);
                $this->getDoctrine()->getManager()->flush();
            }
            
            foreach($anyadeCancionPlaylistUsuario as $anyadirUsu){
                $this->getDoctrine()->getManager()->remove($anyadirUsu);
                $this->getDoctrine()->getManager()->flush(); 
            }

            foreach($anyadeCancionPlaylistPlaylist as $anyadirPlaylist){
                $this->getDoctrine()->getManager()->remove($anyadirPlaylist);
                $this->getDoctrine()->getManager()->flush(); 
            }
            

            foreach($favUsuario as $fav){
                $this->getDoctrine()->getManager()->remove($fav);
                $this->getDoctrine()->getManager()->flush();
            }

            foreach($configUsuario as $config){
                $this->getDoctrine()->getManager()->remove($config);
                $this->getDoctrine()->getManager()->flush(); 
            }

            foreach($premiumUsuario as $premium){
                $this->getDoctrine()->getManager()->remove($premium);
                $this->getDoctrine()->getManager()->flush();    
            }

            foreach($freeUsuario as $free){
                $this->getDoctrine()->getManager()->remove($free);
                $this->getDoctrine()->getManager()->flush();    
            }

            foreach($playlistUsuario as $playlist){
                $this->getDoctrine()->getManager()->remove($playlist);
                $this->getDoctrine()->getManager()->flush();
            }

            
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
            ->findOneBy(["id" => $usuarioId]);

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

    public function playlistsByUsuarioId(SerializerInterface $serializer, Request $request){
        $usuarioId = $request->get('usuarioId');
        $playlistId = $request->get('playlistId');

        $playlists = $this->getDoctrine()->getRepository(Playlist::class)
        ->findOneBy(["id"=> $playlistId,"usuario"=> $usuarioId]);

        $playlistActiva = $this->getDoctrine()->getRepository(Activa::class)
        ->findOneBy(["playlist" => $playlists]);


        if ($request->isMethod("GET")){
            

            $playlists = $serializer->serialize(
                $playlists,
                'json',
                ['groups' => ['playlist']]
            );

            return new Response($playlists);
        }
        

        if($request->isMethod("PUT")){
            if (!empty($playlists)){
                $bodyData=$request->getContent();
                $playlists=$serializer->deserialize(
                $bodyData,
                Playlist::class,
                'json',
                ['object_to_populate'=>$playlists]
            ); 
            

            $this->getDoctrine()->getManager()->persist($playlists);
            $this->getDoctrine()->getManager()->flush();
            
            $playlists = $serializer->serialize(
                $playlists,
                'json',
                ['groups'=>['playlist']]

                );

                return new Response($playlists);
            }

            
            return new JsonResponse(["msg" => 'Affiliation not found'], 404);
        }

        if($request->isMethod("DELETE")){
            $deletedPlaylistActiva=clone $playlistActiva;

            $eliminda = new Eliminada();

            $eliminda->setPlaylist($playlists);
            $eliminda->setFechaEliminacion(new \DateTime());

            $this->getDoctrine()->getManager()->persist($eliminda);
            $this->getDoctrine()->getManager()->flush();

            $this->getDoctrine()->getManager()->remove($playlistActiva);
            $this->getDoctrine()->getManager()->flush();


            $playlists = $serializer->serialize(
                $playlists,
                'json',
                ['groups' => ['playlist']]
            );

            return new Response($playlists);


            
        }

        
    }

   

    
     
}