<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
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

    public function save(Comments $entity, bool $flush = false): void
    {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
    }

    public function remove(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLastComments(): array
   {
        return $this->createQueryBuilder('c')
            ->select("c,u")
            ->leftJoin('c.authorId','u')
            ->orderBy('c.creatDateTime', 'DESC')
            ->getQuery()->getArrayResult();
   }

    public function getCommentByPageId($pageId): array
    {
        return $this->createQueryBuilder('c')
            ->select("c,r,u,uu")
            ->leftJoin('c.responses','r')
            ->leftJoin('c.authorId','u')
            ->leftJoin('r.authorId','uu')
            ->setParameter('val', $pageId)
            ->andWhere('c.pageId = :val')
            ->andWhere('c.parentId is null')
            ->orderBy('c.creatDateTime', 'DESC')
            ->getQuery()->getArrayResult();
    }

}
