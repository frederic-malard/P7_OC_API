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
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
     */
    public function getAllCustomers(CustomerRepository $repo, SerializerInterface $serializer)
    {
        if($this->isGranted("ROLE_ADMIN"))
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
     *      path="/api/customers/{id}",
     *      name="get_one_customer",
     *      methods={"GET"}
     * )
    * @param Customer $customerEntity
    * @param SerializerInterface $serializer
    * @return void
    */
    public function getOneCustomer(Customer $customerEntity, SerializerInterface $serializer)
    {
        if($this->isGranted("ROLE_ADMIN"))
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
     *      path="/api/customers",
     *      name="post_customer",
     *      methods={"POST"}
     * )
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function postCustomer(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        if($this->isGranted("ROLE_ADMIN"))
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

            $errorsEntities = $validator->validate($customerEntity);
    
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

            $manager->persist($customerEntity);
            $manager->flush();

            $responseArray = ["lien" => "/api/customers/" . $customerEntity->getId()];

            $responseJson = $serializer->encode(
                $responseArray,
                "json"
            );

            return new JsonResponse(
                $responseJson,
                Response::HTTP_CREATED,
                [
                    "location" => "/api/customers/" . $customerEntity->getId()
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
     *      path="/api/customers/{id}",
     *      name="put_customer",
     *      methods={"PUT"}
     * )
     * @param Customer $customerEntity
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return void
     */
    public function putCustomer(Customer $customerEntity, EntityManagerInterface $manager, SerializerInterface $serializer, Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        if($this->isGranted("ROLE_ADMIN"))
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

            $errorsEntities = $validator->validate($customerEntity);
    
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

            $manager->persist($customerEntity);
            $manager->flush();

            $responseArray = ["lien" => "/api/customers/" . $customerEntity->getId()];

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
     *      path="api/customers/{id}",
     *      name="delete_customer",
     *      methods={"DELETE"}
     * )
     * @param Customer $customerEntity
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function deleteCustomer(Customer $customerEntity, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        if($this->isGranted("ROLE_ADMIN"))
        {
            if (count($customerEntity->getRoles()) > 1 && $customerEntity->getRoles()[1] == "ROLE_ADMIN")
            {
                $responseArray = [
                    "code" => "403",
                    "message" => "Il s'agit d'un compte admin. Vous ne pouvez pas le supprimer."
                ];
    
                $responseJson = $serializer->encode(
                    $responseArray,
                    "json"
                );
    
                return new JsonResponse(
                    $responseJson,
                    400,
                    [],
                    true
                );
            }
            else
            {
                $manager->remove($customerEntity);
                $manager->flush();
        
                $responseArray = [
                    "code" => "200",
                    "message" => "Client supprimé."
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
