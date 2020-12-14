<?php

namespace App\Entity;

use App\Repository\UserRepository; // no lo borres pendejo que sino no andan los repositorios
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    const USER_REGISTERED = "User has been created";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $role = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var boolean The banned account
     * @ORM\Column(type="boolean")
     */
    private $banned;

    /**
     * @var string The name of the user
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="user")
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Posts", mappedBy="user")
     */
    private $posts;

    public function __construct()
    {
        $this->banned = false;
        $this->role = ["ROLE_USER"];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRole(): array
    {
        $role = $this->role;
        // guarantee every user at least has ROLE_USER
        $role[] = 'ROLE_USER';

        return array_unique($role);
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getBanned(): bool
    {
        return  $this->banned;
    }

    public function setBanned($banned): void
    {
        $this->banned = $banned;
    }

    public function getName(): string
    {
        return  $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getRoles(): array
    {
        $role = $this->role;
        // guarantee every user at least has ROLE_USER
        $role[] = 'ROLE_USER';

        return array_unique($role);
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
