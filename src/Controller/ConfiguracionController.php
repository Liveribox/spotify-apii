<?php

namespace App\Controller;

use App\Entity\Idioma;
use App\Entity\Usuario;
use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\TipoDescarga;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ConfiguracionController extends AbstractController
{
    public function configuracionByUsuario(SerializerInterface $serializer, Request $request){
        $usuarioId = $request->get("usuario_id");

        $configuraciones = $this->getDoctrine()
            ->getRepository(Configuracion::class)
            ->findOneBy(["usuario" => $usuarioId]);

        if ($request->isMethod("GET")){

            $configuraciones = $serializer->serialize(
                $configuraciones,
                'json',
                ["groups" => ["configuracion"]] 
            );

            return new Response($configuraciones);
        }

        if ($request->isMethod("PUT")){
                if (!empty($configuraciones)){
                    $bodyData=$request->getContent();
                    $configuraciones=$serializer->deserialize(
                    $bodyData,
                    Configuracion::class,
                    'json',
                    ['object_to_populate'=>$configuraciones]
                );

                $idioma = $this->getDoctrine()->getRepository(Idioma::class)
                ->findOneBy(["id" => "3"]);

                $calidad = $this->getDoctrine()->getRepository(Calidad::class)
                ->findOneBy(["id" => "1"]);

                $tipoDescarga = $this->getDoctrine()->getRepository(TipoDescarga::class)
                ->findOneBy(["id" => "1"]);



                $configuraciones->setIdioma($idioma);
                $configuraciones->setCalidad($calidad);
                $configuraciones->setTipoDescarga($tipoDescarga);



                $this->getDoctrine()->getManager()->persist($configuraciones);
                $this->getDoctrine()->getManager()->flush();

                $configuraciones = $serializer->serialize(
                    $configuraciones,
                    'json',
                    ['groups'=>['configuracion']]
                );
                
                return new Response($configuraciones);
            }

            
        }

        
    }
}