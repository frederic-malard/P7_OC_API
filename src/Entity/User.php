<?php

namespace App\Entity;

use Swagger\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("mail")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({
     *      "getAllUsers",
     *      "getOneUser"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({
     *      "getAllUsers",
     *      "getOneUser",
     *      "userSwg"
     * })
     * @SWG\Property(type="string", maxLength=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({
     *      "getAllUsers",
     *      "getOneUser",
     *      "userSwg"
     * })
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email(
     *      message="{{ value }} n'est pas un email valide."
     * )
     * @Groups({
     *      "getAllUsers",
     *      "getOneUser",
     *      "userSwg"
     * })
     */
    private $mail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="users")
     * @Groups({
     *      "userSwg"
     * })
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Groups({
     *      "getAllUsers",
     *      "getOneUser",
     *      "userSwg"
     * })
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password/*, UserPasswordEncoderInterface $encoder*/): self
    {
        // $user = new User();
        // $this->password = $encoder->encodePassword($user, $password);
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUserName()
    {
        return $this->getMail();
    }

    public function eraseCredentials(){}
}
