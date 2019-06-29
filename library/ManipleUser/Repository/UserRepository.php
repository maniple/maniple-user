<?php

// use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class ManipleUser_Repository_UserRepository extends EntityRepository
{
    /*
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        // to avoid n+1 query problem users are fetched along with their primary affiliation
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('User', 'Affil')
            ->from('Maniple\ModUser\Entity\User', 'User')
            ->leftJoin('User.affil', 'Affil')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;

        $c = new Criteria();
        foreach ($criteria as $key => $value) {
            $c->where($c->expr()->eq($key, $value));
        }

        if ($orderBy === null) {
            $orderBy = array('sortName' => 'ASC');
        }
        $c->orderBy($orderBy);

        $qb->addCriteria($c);

        return $qb->getQuery()->getResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $result = $this->findBy($criteria, $orderBy, 1);
        return isset($result[0]) ? $result[0] : null;
    }
    */
}
