<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $bigPhone = new Phone();

        // $bigPhone
        //     ->setPrice(1380)
        //     ->setDAS(0.82)
        //     ->setModel("big phone 4")
        //     ->setReleaseDate(new \DateTime())
        //     ->set()
        //     ->set()
        //     ->set()
        //     ->set()
        //     ->set()
        //     ->set()
        //     ->set()
        //     ->set()
        //     ->set()
        //     ->set()
        // ;

        $customer = new Customer();
        $customer2 = new Customer();

        $customer
            ->setLogin('orange')
            ->setPassword(
                $this->encoder->encodePassword(
                    $customer,
                    'mdpOrange'
                )
            )
            ->setSociety('orange')
        ;

        $customer2
            ->setLogin('pdgSfr')
            ->setPassword(
                $this->encoder->encodePassword(
                    $customer,
                    'mdpSfr'
                )
            )
            ->setSociety('sfr')
        ;

        $manager->persist($customer);
        $manager->persist($customer2);

        $manager->flush();
    }
}
