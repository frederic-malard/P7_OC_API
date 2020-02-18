<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{
    /**
     * @Route("/api/phones", name="get_all_phones", methods={"GET"})
     */
    public function getAllPhones(PhoneRepository $repo, SerializerInterface $serializer)
    {
        $phonesEntity = $repo->findAll();
        
        $phonesJson = $serializer->serialize(
            $phonesEntity,
            "json",
            [
                "groups" => ["getAllPhones"]
            ]
        );

        return new JsonResponse(
            $phonesJson,
            200,
            [],
            true
        );
    }

    /**
     * @Route(
     *      path="/api/phones/{id}",
     *      name="get_one_phone",
     *      methods={"GET"}
     * )
     */
    public function getOnePhone(Phone $phoneEntity, SerializerInterface $serializer)
    {
        $phoneJson = $serializer->serialize(
            $phoneEntity,
            "json",
            [
                "groups" => ["getOnePhone"]
            ]
        );

        return new JsonResponse(
            $phoneJson,
            200,
            [],
            true
        );
    }
}
