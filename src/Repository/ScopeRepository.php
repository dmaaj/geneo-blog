<?php

namespace App\Repository;

use App\Entity\Scope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scope|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scope|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scope[]    findAll()
 * @method Scope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScopeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scope::class);
    }

    // /**
    //  * @return Scope[] Returns an array of Scope objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Scope
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    
    public function setDefaultScope($user)
    {
        foreach($this->findAll() as $scope){
            $scope->addUser($user);
            $this->_em->persist($scope);
        }
        $this->_em->flush();
    }

    public function changePermission($user, $data)
    {
        foreach($data as $key => $perm){
            // replace underscore with fullstop to fit
            // the structure of the permissions stored in the db
            $permission = str_replace('_','.', $key);

            $scope = $this->findOneBy(['grants' => $permission]);

            if($perm){
                $scope->addUser($user);
            }
            else{
                $scope->removeUser($user);
            }
            $this->_em->persist($scope);
        }

        $this->_em->flush();
    }
}
