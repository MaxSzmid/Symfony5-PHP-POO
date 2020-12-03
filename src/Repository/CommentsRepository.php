<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    public function findAllPosts($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT c.comment, c.date_publication, u.name
             FROM App:Comments c
             JOIN c.user u
             WHERE c.post =' . "$id"
        )->execute();
    }

    public function findCommentActivity($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT p.titulo, p.id as postId, Count(p.id) as cuantityOfComments, c.date_publication as date, c.comment
             FROM App:Comments c
             JOIN c.post p
             WHERE c.user=' . $id . '
             GROUP BY p.id
             ORDER BY c.date_publication DESC'


        )->execute();
    }
    // /**
    //  * @return Comments[] Returns an array of Comments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comments
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
