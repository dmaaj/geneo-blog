<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
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
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function create(Object $dto, Object $user)
    {
        $post = new Post();
        $post->setTitle($dto->title)->setContent($dto->content)
            ->setAuthor($user);
        $this->_em->persist($post);
        $this->_em->flush();
    }

    public function update(Object $dto, Post $post)
    {
        $post->setTitle($dto->title)->setContent($dto->content);
        $this->_em->persist($post);
        $this->_em->flush();
    }

    
    public function delete(Post $post)
    {
        $this->_em->remove($post);
        $this->_em->flush();
    }

    /**
     * Order posts by created_at
     * @return Collection
     */
    public function getLatest()
    {
        return $this->findBy([], ['created_at' => 'DESC']);
    }

    /**
     * Get Single Post by author and post slug
     * @param [type] $author
     * @param [type] $slug
     * @return Post $post
     */
    public function getSinglePost($author, $slug)
    {
        $query = $this->createQueryBuilder('e')
            ->addSelect('u') // to make Doctrine actually use the join
            ->leftJoin('e.author', 'u')
            ->where('e.slug= :slug')
            ->andWhere('u.username= :author')
            ->setParameters(['slug' => $slug, 'author' => $author])
            ->getQuery();

        return $query->getSingleResult();
    }
}
