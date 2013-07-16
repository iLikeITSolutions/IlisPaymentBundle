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
use Ilis\Bundle\PaymentBundle\Event\TransactionUpdatedEvent;

class TransactionSubscriber implements EventSubscriber
{

    /**
     * @var string
     */
    private $identifierSuffix;

    /**
     * @var ContainerAwareEventDispatcher
     */
    private $dispatcher;

    /**
     * @param $suffix string
     */
    public function __construct($suffix, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->identifierSuffix = $suffix;
        $this->dispatcher = $dispatcher;
    }


    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate'
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

        $entity->setIdentifier($this->identifierSuffix);

        $em = $args->getEntityManager();
        $em->persist($entity);
        $em->flush();

        $this->dispatcher->dispatch(
            PaymentEvents::TRANSACTION_CREATED,
            new TransactionCreatedEvent($entity)
        );

    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Transaction)
            return;

        $this->dispatcher->dispatch(
            PaymentEvents::TRANSACTION_UPDATE,
            new TransactionUpdatedEvent($entity)
        );

    }

}