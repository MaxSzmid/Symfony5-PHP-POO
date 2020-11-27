<?php

namespace App\Entity;

use App\Repository\CommentsRepository; // no lo borres pendejo que sino no andan los repositorios
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string")
     */
    private $date_publication;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comentarios")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Posts", inversedBy="comments")
     */
    private $post;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->post;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($post): void
    {
        $this->post = $post;
    }

    public function getDatePublication(): ?string
    {
        return $this->date_publication;
    }

    public function setDatePublication(string $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function generateDate(): ?string
    {
        date_default_timezone_set('America/Argentina/Cordoba');
        $date = date('Y-m-d H:i:s');
        return $date;
    }
}
