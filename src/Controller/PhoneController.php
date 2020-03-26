<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PhoneController extends AbstractController
{
    /**
     * @Route("/api/phones", name="get_all_phones", methods={"GET"})
     */
    public function getAllPhones(PhoneRepository $repo, SerializerInterface $serializer, CacheInterface $cache)
    {
        $phonesJson = $cache->get(
            "phonesJsonCache",
            function (ItemInterface $item) use ($repo, $serializer) {
                $item->expiresAfter(30);
                
                $phonesEntity = $repo->findAll();
            
                $phonesArray = $serializer->normalize(
                    $phonesEntity,
                    null,
                    [
                        "groups" => ["getAllPhones"]
                    ]
                );
            
                for ($i = 0 ; $i < count($phonesArray) ; $i++) {
                    $phonesArray[$i]["link"] = "/api/phones/" . $phonesArray[$i]["id"];
                }
            
                $phonesJson = $serializer->encode(
                    $phonesArray,
                    "json"
                );

                return $phonesJson;
            }
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

    /**
     * @Route(
     *      path="/api/phones",
     *      name="post_phone",
     *      methods={"POST"}
     * )
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function postPhone(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        if($this->isGranted("ROLE_ADMIN"))
        {
            $phoneJson = $request->getContent();

            $phoneEntity = $serializer->deserialize(
                $phoneJson,
                Phone::class,
                "json"
            );

            $errorsEntities = $validator->validate($phoneEntity);
    
            if (count($errorsEntities))
            {
                $errorsJson = $serializer->serialize(
                    $errorsEntities,
                    "json"
                );
    
                return new JsonResponse(
                    $errorsJson,
                    400,
                    [],
                    true
                );
            }

            $manager->persist($phoneEntity);
            $manager->flush();

            $responseArray = ["lien" => "/api/phones/" . $phoneEntity->getId()];

            $responseJson = $serializer->encode(
                $responseArray,
                "json"
            );

            return new JsonResponse(
                $responseJson,
                Response::HTTP_CREATED,
                [
                    "location" => "/api/phones/" . $phoneEntity->getId()
                ],
                true
            );
        }
        else
        {
            $responseArray = [
                "code" => "403",
                "message" => "Vous devez être admin pour envoyer cette requête"
            ];

            $responseJson = $serializer->encode(
                $responseArray,
                "json"
            );

            return new JsonResponse(
                $responseJson,
                403,
                [],
                true
            );
        }
    }

    /**
     * @Route(
     *      path="/api/phones/{id}",
     *      name="put_phone",
     *      methods={"PUT"}
     * )
     * @param Phone $phoneEntity
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return void
     */
    public function putPhone(Phone $phoneEntity, EntityManagerInterface $manager, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        if($this->isGranted("ROLE_ADMIN"))
        {
            $phoneJson = $request->getContent();
            
            $serializer->deserialize(
                $phoneJson,
                Phone::class,
                "json",
                [
                    'object_to_populate' => $phoneEntity
                ]
            );

            $errorsEntities = $validator->validate($phoneEntity);
    
            if (count($errorsEntities))
            {
                $errorsJson = $serializer->serialize(
                    $errorsEntities,
                    "json"
                );
    
                return new JsonResponse(
                    $errorsJson,
                    400,
                    [],
                    true
                );
            }

            $manager->persist($phoneEntity);
            $manager->flush();

            $responseArray = ["lien" => "/api/phones/" . $phoneEntity->getId()];

            $responseJson = $serializer->encode(
                $responseArray,
                "json"
            );

            return new JsonResponse(
                $responseJson,
                200,
                [],
                true
            );
        }
        else
        {
            $responseArray = [
                "code" => "403",
                "message" => "Vous devez être admin pour envoyer cette requête"
            ];

            $responseJson = $serializer->encode(
                $responseArray,
                "json"
            );

            return new JsonResponse(
                $responseJson,
                403,
                [],
                true
            );
        }
    }

    /**
     * @Route(
     *      path="api/phones/{id}",
     *      name="delete_phone",
     *      methods={"DELETE"}
     * )
     * @param Phone $phoneEntity
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function deletePhone(Phone $phoneEntity, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        if($this->isGranted("ROLE_ADMIN"))
        {
            $manager->remove($phoneEntity);
            $manager->flush();

            $responseArray = [
                "code" => "200",
                "message" => "Téléphone supprimé"
            ];

            $responseJson = $serializer->encode(
                $responseArray,
                "json"
            );

            return new JsonResponse(
                $responseJson,
                200,
                [],
                true
            );
        }
        else
        {
            $responseArray = [
                "code" => "403",
                "message" => "Vous devez être admin pour envoyer cette requête"
            ];

            $responseJson = $serializer->encode(
                $responseArray,
                "json"
            );

            return new JsonResponse(
                $responseJson,
                403,
                [],
                true
            );
        }
    }
}
