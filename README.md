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

The only payment method available so far is the "Redsys (Webservice)". In order to use it you have to add
your configuration for this method. 

``` sql
    INSERT INTO ilis_payment_method_configs (`method_id`, `status`) VALUES (
        (SELECT id  FROM ilis_payment_methods WHERE code = 'redsys-webservice'),
    	1
    )
```

The Redsys method requires 4 attributes to be configured (merchant, terminal, secretKey, environment)

Here is an example on how configure the merchant attribute for your newly created configuration

``` sql
    INSERT INTO ilis_payment_method_config_attributes (
        `attribute_id`, 
    	`config_id`, 
    	`value`
    )
    VALUES (
    	(SELECT id FROM ilis_payment_method_attributes WHERE name = 'merchant' AND method_id = (SELECT id FROM ilis_payment_methods WHERE code = 'redsys-webservice')),
    	(SELECT id FROM ilis_payment_method_configs WHERE method_id = (SELECT id FROM ilis_payment_methods WHERE code = 'redsys-webservice')),
    	<your-merchant-id>
    )
```

Where "your-merchant-id" is the value that the Bank will provide you together with the other attributes' values.
The others attributes can be configured in the same way, just change the attribute name an value accordingly.

The possible values for environment are "testing", "integration", "production"


## Usage

The main service you will use is the [Ilis\Bundle\PaymentBundle\Service](Service/Manager.php) that provides you with the methods to:

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
                'We were unable to process this transaction. Error code is',
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









