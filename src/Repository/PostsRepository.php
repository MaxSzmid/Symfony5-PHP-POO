<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    // /**
    // * @return Posts [] return an array of Posts without trash data.
    // */

    /* este da los datos
    public function getAllPosts()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT p.id, p.titulo, p.foto, p.fecha_publicacion FROM App:Posts p'
        )->getResult();
    }*/
    //este envia la query unicamente
    public function getAllPostsQuery()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT p.id, p.titulo, p.foto, p.fecha_publicacion, u.name 
             FROM App:Posts p
             JOIN p.user u'
        );
    }

    public function getPost($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT p.id, p.titulo, p.likes, p.foto, p.fecha_publicacion, p.contenido, u.name 
             FROM App:Posts p
             JOIN p.user u
             WHERE p.id='."$id"
        )->execute();
    }



    // /**
    //  * @return Posts[] Returns an array of Posts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Posts
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
