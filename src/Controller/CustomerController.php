<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomerController extends AbstractController
{
    /**
     * @Route("/api/customers", name="get_all_customers", methods={"GET"})
     * @param CustomerRepository $repo
     * @param SerializerInterface $serializer
     * @return void
     * @IsGranted("ROLE_ADMIN")
     */
    public function getAllCustomers(CustomerRepository $repo, SerializerInterface $serializer)
    {
        $customersEntity = $repo->findAll();

        $customersJson = $serializer->serialize(
            $customersEntity,
            "json",
            [
                "groups" => ["getAllCustomers"]
            ]
        );

        // $customersArray = $serializer->normalize(
        //     $customersEntity,
        //     null,
        //     [
        //         "groups" => ["getAllCustomers"]
        //     ]
        // );

        // for ($i = 0 ; $i < count($customersArray) ; $i++) {
        //     $customersArray[$i]["link"] = "/api/customers/" . $customersArray[$i]["id"];
        // }

        // $customersJson = $serializer->encode(
        //     $customersArray,
        //     "json"
        // );

        return new JsonResponse(
            $customersJson,
            200,
            [],
            true
        );
    }

    /**
     * @Route(
     *      path="/api/customers/{id}",
     *      name="get_one_customer",
     *      methods={"GET"}
     * )
    * @param Customer $customerEntity
    * @param SerializerInterface $serializer
    * @return void
     * @IsGranted("ROLE_ADMIN")
    */
    public function getOneCustomer(Customer $customerEntity, SerializerInterface $serializer)
    {
        $customerJson = $serializer->serialize(
            $customerEntity,
            "json",
            [
                "groups" => ["getOneCustomer"]
            ]
        );

        return new JsonResponse(
            $customerJson,
            200,
            [],
            true
        );
    }

    /**
     * @Route(
     *      path="/api/customers",
     *      name="post_customer",
     *      methods={"POST"}
     * )
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     * @IsGranted("ROLE_ADMIN")
     */
    public function postCustomer(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $customerJson = $request->getContent();

        $customerEntity = $serializer->deserialize(
            $customerJson,
            Customer::class,
            "json"
        );

        $customerEntity
            ->setPassword($encoder->encodePassword(
                $customerEntity,
                $customerEntity->getPassword()
            ))
        ;

        $manager->persist($customerEntity);
        $manager->flush();

        return new JsonResponse(
            "client ajouté",
            Response::HTTP_CREATED,
            [
                "location" => "/api/customers/" . $customerEntity->getId()
            ],
            true
        );
    }

    /**
     * @Route(
     *      path="/api/customers/{id}",
     *      name="put_customer",
     *      methods={"PUT"}
     * )
     * @param Customer $customerEntity
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return void
     * @IsGranted("ROLE_ADMIN")
     */
    public function putCustomer(Customer $customerEntity, EntityManagerInterface $manager, SerializerInterface $serializer, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $customerJson = $request->getContent();

        $changedPassword = false;

        if (isset($customerJson['password']))
            $changedPassword = true;
        
        $serializer->deserialize(
            $customerJson,
            Customer::class,
            "json",
            [
                'object_to_populate' => $customerEntity
            ]
        );

        if ($changedPassword)
        {
            $customerEntity
                ->setPassword($encoder->encodePassword(
                    $customerEntity,
                    $customerEntity->getPassword()
                ))
            ;
        }

        $manager->persist($customerEntity);
        $manager->flush();

        return new JsonResponse(
            "Modification sauvées",
            200
        );
    }

    /**
     * @Route(
     *      path="api/customers/{id}",
     *      name="delete_customer",
     *      methods={"DELETE"}
     * )
     * @param Customer $customerEntity
     * @param EntityManagerInterface $manager
     * @return void
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCustomer(Customer $customerEntity, EntityManagerInterface $manager)
    {
        if (count($customerEntity->getRoles()) > 1 && $customerEntity->getRoles()[1] == "ROLE_ADMIN")
        {
            return new JsonResponse(
                "Il s'agit d'un compte admin. Vous ne pouvez pas le supprimer.",
                400
            );
        }
        else
        {
            $manager->remove($customerEntity);
            $manager->flush();
    
            return new JsonResponse(
                "utilisateur supprimé.",
                200
            );
        }
    }
}
