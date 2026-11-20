<?php

namespace App\Repository;

use App\Entity\Server;
use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function findValidToken(string $value): ?Token
    {
        $query = $this->createQueryBuilder('token');

        $query->where('token.value = :value');
        $query->andWhere('token.expireAt >= :now');

        $query->setParameter('value', $value);
        $query->setParameter('now', new \DateTime());

        return $query->getQuery()->getOneOrNullResult();
    }
}
