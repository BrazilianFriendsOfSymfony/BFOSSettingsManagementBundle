<?php

namespace BFOS\SettingsManagementBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

/**
 * SettingRepository
 */
class SettingRepository extends EntityRepository
{
    public function getByType($type)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.type =:type')
            ->setParameter('type', $type);

        return $qb->getQuery()->getResult();
    }

    public function getByName($name)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.name =:name')
            ->setParameter('name', $name);

        $qb->getFirstResult();

        $result = $qb->getQuery()->getResult();

        return $result[0];
    }

    public function getValue($name)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.name =:name')
            ->setParameter('name', $name);

        $qb->getFirstResult();

        return $qb->getQuery()->getResult();
    }
}
