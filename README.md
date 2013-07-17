PaymentBundle
=============

## Installation

### Add the dependency to the composer.json

```js
{
    "require": {
        "ilis/payment-bundle": "dev-master"
    }
}
```

Since the bundle is not in packagist (yet) you should also add the repository into the repositories section

```js
{
    "repositories": [
            {
                "type":"vcs",
                "url":"git@github.com:iLikeITSolutions/IlisPaymentBundle.git"
            }
    ],
}
```

### Download the bundle using composer

``` bash
  $ php composer.phar update ilis/payment-bundle
```

### Enable the bundle in the AppKernel

``` php
  <?php
  // app/AppKernel.php
  
  public function registerBundles()
  {
      $bundles = array(
          // ...
          new Ilis\Bundle\PaymentBundle\IlisPaymentBundle(),
      );
  }
```

### Update the db in your application

We plan to create a Command to install the bundle that will include creating the needed db tables, loading fixtures etc.
Since this is still not available, please follow the next steps to update the database

``` bash
  $ php app/console doctrine:schema:update
  $ php app/console doctrine:fix:load \
    --append \
    --fixtures vendor/ilis/payment-bundle/Ilis/Bundle/PaymentBundle/Provider/Redsys/DataFixtures/ORM

```

### Configure the Payment Methods

The available payments methods can be enabled and configured editing the *methods* section of the bundle's configuration.

In order to use the bundle you have to configure at least one payment method. 
So far the only available method is *redsys_webservice*.

Here is an example configuration:

``` yaml

ilis_payment:
    methods:
        redsys_webservice:
           merchant: <your_merchant_id>
           secret_key: <your_secret_key>
           terminal: <your_terminal>
           environment: "testing"
```

This makes the *redsys_webservice* method available to be used in your application.
Possible values for the environment parameter are: "testing", "integration", "production", being "production" the default value.

Of course you can always dump the configuration reference and get more info about the default/required paramenters using your application console 

``` bash

php app/console config:dump-reference IlisPaymentBundle

```

Even though it doens't make much sense now that only one payment method is available, but keep in mind that already configured payment methods can be disabled in two ways:

* By removing the corresponding node from the methods sections
* setting the *enabled* parameter to false. This is particulary useful if you want temporary disable the method but keep the configuration value to re-enable later.

``` yaml 

ilis_payment:
    methods:
        redsys_webservice:
           enabled: false
           merchant: <your_merchant_id>
           secret_key: <your_secret_key>
           terminal: <your_terminal>
           environment: testing
```


## Usage

The main service you will use is the [Ilis\Bundle\PaymentBundle\Service\Manager](Service/Manager.php) that provides you with the methods to:

* Get available payment methods
* Get payment methods configurations
* Process Transactions

The service is labeled *ilis.payment.manager*

Here is an example of a typical usage in a Controller to process a CreditCard AUTH Transaction

``` php
    
    // ...
    use Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard as CreditCardTransaction;
    use Ilis\Bundle\PaymentBundle\Form\Type\CreditCardType;
    use Ilis\Bundle\PaymentBundle\Service\Manager as TransactionManager;
    
    // ... 
    // Inside your controller action
    /** @var $manager  TransactionManager */
    $manager = $this->get('ilis.payment.manager');

    // Retrieve available payment methods
    $methods = $manager->getPaymentMethods(true);
    // Since we only have one method integrated, just use that
    $method = array_shift($methods);

    /** @var CreditCardTransactoin  */
    $transaction = new CreditCardTransaction;
    // Setup transaction
    $transaction->setMethod($method);
    $transaction->setType(CreditCardTransaction::TYPE_AUTH);
    $transaction->setAmount(2.50);

    // Create the form
    $form = $this->createForm(new CreditCardType(), $transaction);

    $request = $this->getRequest();

    // Process form submission
    if ($request->isMethod('POST'))
    {
        $form->bind($request);

        if ($form->isValid())
        {
            $manager->processTransaction($transaction);

            if ($transaction->getStatus() === CreditCardTransaction::STATUS_SUCCESS)
                return $this->redirect($this->generateUrl('payment_success'));

            else $form->addError(new FormError(sprintf(
                'We were unable to process this transaction. Error code is %s',
                $transaction->getStatusCode()
            )));
        }
    }

    // Render the view
    return $this->render(
        'PaymentBundle:Default:index.html.twig', array(
            'form'  => $form->createView()

    ));

```

### Transaction identifier

Since most of the payment providers require to send an unique transaction identifier when a transaction is 
initiated, we use a special field of the transactions table, that is the "identifier".

The indentifier is nothing else than a string made by the \<TRANSACTION_ID\> and a \<TRANSACTION_IDENTIFIER_SUFFIX\>

By default we use the kernel.environment global container parameter as identifier so that, for example,  in
your dev environment the transaction identifiers will look like this

*"00000123-dev"*

In addiction the suffix can be set in the bundle configuration for complete control over it.

Here is how you the configuration of you *config.yml* should look like if you want to use "LOCAL" as transaction identifier prefix:


``` yml
ilis_payment:
    transaction_identifier_prefix: "LOCAL"
    
```

*Please note that in production environment the suffix will NOT take effect and no suffix is going to be used.*

### Transaction Events

During the transaction processing there are couple of events that are fired: 

* ilis.payment.transaction.created
* ilis.payment.transaction.processed

This allow you to register listeners for these events. Here is some example code on how to do that in your application.

``` php

<?php

namespace Acme\PaymentBundle\EventListener;

use Ilis\Bundle\PaymentBundle\Event\TransactionCreatedEvent;
use Ilis\Bundle\PaymentBundle\Event\TransactionProcessedEvent;
use Monolog\Logger;


class Transaction
{

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Event\TransactionCreatedEvent $event
     */
    public function onTransactionCreated(TransactionCreatedEvent $event)
    {
        $transaction = $event->getTransaction();

        $this->logger->debug(
            sprintf(
                'Notified %s  (Identifier: %s)',
                $event->getName(),
                $transaction->getIdentifier()
        ));
    }

    /**
     * @param \Ilis\Bundle\PaymentBundle\Event\TransactionProcessedEvent $event
     */
    public function onTransactionProcessed(TransactionProcessedEvent $event)
    {
        $transaction = $event->getTransaction();

        $this->logger->debug(
            sprintf(
                'Notified %s  (Identifier: %s)',
                $event->getName(),
                $transaction->getIdentifier()
            ));
    }

}


``` 

``` yml

parameters:
     payment.transaction_listener.class: Acme\PaymentBundle\EventListener\Transaction

services:

    transaction_listener:
        class: %payment.transaction_listener.class%
        arguments: [@logger]
        tags:
              - { name: ilis.payment.event_listener, event: ilis.payment.transaction.created, method: onTransactionCreated }
              - { name: ilis.payment.event_listener, event: ilis.payment.transaction.processed, method: onTransactionProcessed }


```


















