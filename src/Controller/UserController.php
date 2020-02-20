<?php

namespace App\Controller;

use App\Entity\User;
use Swagger\Annotations as SWG;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="get_all_users", methods={"GET"})
     * @param UserRepository $repo
     * @param SerializerInterface $serializer
     * @return void
     */
    public function getAllUsers(UserRepository $repo, SerializerInterface $serializer)
    {
        $usersEntity = $repo->findByCustomer($this->getUser());

        $usersArray = $serializer->normalize(
            $usersEntity,
            null,
            [
                "groups" => ["getAllUsers"]
            ]
        );

        for ($i = 0 ; $i < count($usersArray) ; $i++) {
            $usersArray[$i]["link"] = "/api/users/" . $usersArray[$i]["id"];
        }

        $usersJson = $serializer->encode(
            $usersArray,
            "json"
        );

        return new JsonResponse(
            $usersJson,
            200,
            [],
            true
        );
    }

    /**
     * @Route(
     *      path="/api/users/{id}",
     *      name="get_one_user",
     *      methods={"GET"}
     * )
    * @param User $userEntity
    * @param SerializerInterface $serializer
    * @return void
    */
    public function getOneUser(User $userEntity, SerializerInterface $serializer)
    {
        if ($userEntity->getCustomer() == $this->getUser())
        {
            $userJson = $serializer->serialize(
                $userEntity,
                "json",
                [
                    "groups" => ["getOneUser"]
                ]
            );

            return new JsonResponse(
                $userJson,
                200,
                [],
                true
            );
        }
        else
        {
            return new JsonResponse(
                "Cet utilisateur n'est pas un de vos clients.",
                400
            );
        }
    }

    /**
     * @Route(
     *      path="/api/users",
     *      name="post_user",
     *      methods={"POST"}
     * )
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function postUser(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $userJson = $request->getContent();

        $userEntity = $serializer->deserialize(
            $userJson,
            User::class,
            "json"
        );

        $userEntity
            ->setCustomer($this->getUser())
            ->setPassword($encoder->encodePassword(
                $userEntity,
                $userEntity->getPassword()
            ))
        ;

        $manager->persist($userEntity);
        $manager->flush();

        return new JsonResponse(
            "utilisateur ajouté",
            Response::HTTP_CREATED,
            [
                "location" => "/api/users/" . $userEntity->getId()
            ],
            true
        );
    }

    /**
     * @Route(
     *      path="/api/users/{id}",
     *      name="put_user",
     *      methods={"PUT"}
     * )
     * @param User $userEntity
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return void
     */
    public function putUser(User $userEntity, EntityManagerInterface $manager, SerializerInterface $serializer, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $userJson = $request->getContent();

        $changedPassword = false;

        if (isset($customerJson['password']) && $customerJson['password'] != null  && $customerJson['password'] != "")
            $changedPassword = true;
        
        $serializer->deserialize(
            $userJson,
            User::class,
            "json",
            [
                'object_to_populate' => $userEntity
            ]
        );

        $userEntity
            ->setPassword($encoder->encodePassword(
                $userEntity,
                $userEntity->getPassword()
            ))
        ;

        $manager->persist($userEntity);
        $manager->flush();

        return new JsonResponse(
            "Modification sauvées",
            200
        );
    }

    /**
     * @Route(
     *      path="api/users/{id}",
     *      name="delete_user",
     *      methods={"DELETE"}
     * )
     * @param User $userEntity
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function deleteUser(User $userEntity, EntityManagerInterface $manager)
    {
        if ($userEntity->getCustomer() == $this->getUser())
        {
            $manager->remove($userEntity);
            $manager->flush();

            return new JsonResponse(
                "utilisateur supprimé.",
                200
            );
        }
        else
        {
            return new JsonResponse(
                "Le client que vous essayez de supprimer n'est pas un des votres.",
                400
            );
        }
    }
}
