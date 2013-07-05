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
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Ilis\Bundle\PaymentBundle\Entity\Transaction;
use Ilis\Bundle\PaymentBundle\PaymentEvents;
use Ilis\Bundle\PaymentBundle\Event\TransactionCreatedEvent;


class TransactionSubscriber implements EventSubscriber
{

    /**
     * @var string
     */
    private $identifierPrefix;

    /**
     * @var ContainerAwareEventDispatcher
     */
    private $dispatcher;

    /**
     * @param $prefix string
     */
    public function __construct($prefix, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->identifierPrefix = $prefix;
        $this->dispatcher = $dispatcher;
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

        $this->dispatcher->dispatch(
            PaymentEvents::TRANSACTION_CREATED,
            new TransactionCreatedEvent($entity)
        );

    }

}