PaymentBundle
=============

## Installation

### Add the dependency to the composer.json

```js
{
    "require": {
        "ilis/payment-bundle": "@dev"
    }
}
```

### Dowload the bundle using composer

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

``` bash
  $ php app/console doctrine:schema:update
```






