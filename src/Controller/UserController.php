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
     * @Route("/api/users/page/{page<\d+>?1}", name="get_all_users_per_page", methods={"GET"})
     * @param UserRepository $repo
     * @param SerializerInterface $serializer
     * @return void
     */
    public function getAllUsers(UserRepository $repo, SerializerInterface $serializer, $page = 1)
    {
        if (is_null($page) || $page < 1)
            $page = 1;

        $usersEntities = $repo->findByCustomer($this->getUser());

        $nbUsers = count($usersEntities);

        if (($page-1)*$_ENV["LIMIT_PAGE"] > $nbUsers)
            $page = ceil(($nbUsers)/$_ENV["LIMIT_PAGE"]);

        if ($nbUsers > $_ENV["LIMIT_PAGE"])
            $usersEntities = array_slice($usersEntities, ($page-1)*$_ENV["LIMIT_PAGE"], min($_ENV["LIMIT_PAGE"], $nbUsers-($page-1)*$_ENV["LIMIT_PAGE"]));

        $usersArray = $serializer->normalize(
            $usersEntities,
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
            $responseArray = [
                "code" => "403",
                "message" => "Cet utilisateur n'est pas un de vos clients !"
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

        $responseArray = ["lien" => "/api/users/" . $userEntity->getId()];

        $responseJson = $serializer->encode(
            $responseArray,
            "json"
        );

        return new JsonResponse(
            $responseJson,
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

        $responseArray = ["lien" => "/api/users/" . $userEntity->getId()];

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
    public function deleteUser(User $userEntity, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        if ($userEntity->getCustomer() == $this->getUser())
        {
            $manager->remove($userEntity);
            $manager->flush();

            $responseArray = [
                "code" => "200",
                "message" => "Utilisateur supprimé."
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
                "message" => "Le client que vous essayez de supprimer n'est pas l'un des votres"
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
