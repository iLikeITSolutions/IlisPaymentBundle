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
  php app/console doctrine:fix:load \
    --append \
    --fixtures vendor/ilis/payment-bundle/Ilis/Bundle/PaymentBundle/Provider/Redsys/DataFixtures/ORM

```








