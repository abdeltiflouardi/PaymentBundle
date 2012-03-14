How to install: 

#### add in your file "deps" this code

     [OSPaymentBundle]
          git=git://github.com/ouardisoft/PaymentBundle.git
          target=/bundles/OS/PaymentBundle

#### Execute

     php bin/vendor install

#### Add in AppKernel.php

     new OS\PaymentBundle\OSPaymentBundle(),

#### Add in autoload.php

     'OS' => __DIR__.'/../vendor/bundles',

#### Plugins

     Resources/doc