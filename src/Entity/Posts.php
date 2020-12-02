<?php

namespace App\Entity;

use App\Repository\PostsRepository; // no lo borres pendejo que sino no andan los repositorios
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostsRepository::class)
 */
class Posts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $foto;

    /**
     * @ORM\Column(type="string")
     */
    private $fecha_publicacion;

    /**
     * @ORM\Column(type="text")
     */
    private $contenido;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getLikes(): ?string
    {
        return $this->likes;
    }

    public function setLikes(?string $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getfecha_publicacion(): ?string
    {
        return $this->fecha_publicacion;
    }

    public function setfecha_publicacion(string $fecha_publicacion): self
    {
        $this->fecha_publicacion = $fecha_publicacion;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }


    public function getComments()
    {
        return $this->comments;
    }


    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    public function generateDate(): ?string
    {
        date_default_timezone_set('America/Argentina/Cordoba');
        $date = date('Y-m-d H:i:s');
        return $date;
    }
}
