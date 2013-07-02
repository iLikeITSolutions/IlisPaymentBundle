<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity;


use Doctrine\ORM\EntityRepository;

class MethodRepository extends EntityRepository
{
    /**
     * @param bool $onlyEnabled
     * @return array
     */
    public function getConfigured($onlyEnabled = true)
    {
        $qb = $this->createQueryBuilder('m')
                ->join('m.configs', 'c');

        if (true === $onlyEnabled){
            $qb->where('c.status = :enabled');
            $qb->setParameter('enabled', true);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Method $method
     * @param bool $onlyEnabled
     * @return int
     */
    public function countConfigurations(Method $method, $onlyEnabled = true)
    {
        $qb = $this->createQueryBuilder('m')
                ->join('m.configs', 'c');

        $qb->select('COUNT (m.id)');

        if (true === $onlyEnabled){
            $qb->where('c.status = :enabled');
            $qb->setParameter('enabled', MethodConfig::STATUS_ENABLED);
        }

        return (int) $qb->getQuery()->getSingleResult();
    }
}