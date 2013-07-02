<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MethodConfigRepository extends  EntityRepository
{
    public function getByMethod(Method $method, $onlyEnabled = true)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.method = :method');
        $qb->setParameter('method', $method);

        if (true == $onlyEnabled)
        {
            $qb->andWhere('c.status = :enabled');
            $qb->setParameter('enabled', MethodConfig::STATUS_ENABLED);
        }

        return $qb->getQuery()->getResult();
    }

}