<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\EventListener\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Ilis\Bundle\PaymentBundle\Entity\Transaction;

class TransactionSubscriber implements EventSubscriber
{

    /**
     * @var string
     */
    private $identifierPrefix;

    /**
     * @param $prefix string
     */
    public function __construct($prefix)
    {
        $this->identifierPrefix = $prefix;
    }


    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist'
        );
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Transaction)
            return;

        $entity->setIdentifier($this->identifierPrefix);

        $em = $args->getEntityManager();
        $em->persist($entity);
        $em->flush();

    }

}