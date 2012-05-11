AvroBlogBundle
====================

A very simple Symfony2 Blog Avro. 

Dependencies
============
-<a href="https://github.com/FriendsOfSymfony/FOSUserBundle">FOSCommentBundle</a>
-FOSUserBundle
-AvroHTMLPurifierBundle

Installation
============
Follow the links above to install the FOSCommentBundle and FOSUserBundle

Add the `Avro` namespace to your autoloader:

``` php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Avro' => __DIR__.'/../vendor/bundles',
));
```

Enable the bundle in the kernel:

``` php
// app/AppKernel.php

    new Avro\BlogBundle\AvroBlogBundle(),
```

```
[AvroBlogBundle]
    git=git://github.com/yourGitHubAccount.git
    target=bundles/Avro/BlogBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

