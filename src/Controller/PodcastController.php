<?php

namespace App\Controller;
use App\Entity\Podcast;
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
    }
}