<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\Security\SecurityConstant;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly SecurityConstant $securityConstant)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function datatable(array $parameters): array
    {
        $qb = $this->createQueryBuilder('u');

        $countQb = (clone $qb)->select('COUNT(DISTINCT u)');

        if ($parameters['search']['value']) {
            $searchItem = $parameters['search']['value'];
            $searchItem = preg_replace("/'/", "''", $searchItem);
            $searchParts = explode(' ', $searchItem);

            foreach ($searchParts as $searchPart) {
                $qb->andWhere(
                    $this->searchByRole($searchItem) .
                    'u.username LIKE \'%' . $searchPart . '%\' OR ' .
                    'u.email LIKE \'%' . $searchPart . '%\''
                );
            }
        }

        foreach ($parameters['orders'] as $order) {
            switch ($order['name']) {
                case 'username':
                    $qb->orderBy('u.username', $order['dir']);
                    break;
                case 'email':
                    $qb->orderBy('u.email', $order['dir']);
                    break;
                case 'roles':
                    $qb->orderBy('u.roles', $order['dir']);
                    break;
                case 'active':
                    $qb->orderBy('u.active', $order['dir']);
                    break;
                case 'verify':
                    $qb->orderBy('u.verify', $order['dir']);
                    break;
                default:
                    break;
            }
        }

        return [
            'result' => $qb->getQuery()->getResult(),
            'count' => $countQb->getQuery()->getSingleScalarResult()
        ];
    }

    public function searchByRole($searchItem): string
    {
        $searchElement = preg_grep('/.*' . $searchItem . '.*/', $this->securityConstant->getTranslatedRoles());
        $res = '';

        foreach ($searchElement as $item) {
            $res .= 'u.roles LIKE \'%'
                . array_search($item, $this->securityConstant->getSyncRoles(), true)
                . '%\' OR ';
        }

        return $res;
    }

    public function findInactive()
    {
        $today = new DateTimeImmutable();
        $inactiveDate = $today->sub(new DateInterval('P7D'));

        return $this->createQueryBuilder('u')
            ->where('u.lastActivityAt <= :inactiveDate')
            ->setParameter('inactiveDate', $inactiveDate->format('Y-m-d 00:00:00'))
            ->getQuery()
            ->getResult();
    }
}
