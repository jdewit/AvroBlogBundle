AvroBlogBundle [![Build Status](https://travis-ci.org/jdewit/AvroBlogBundle.png?branch=master)](https://travis-ci.org/jdewit/AvroBlogBundle)
--------------

Another Symfony2 blog bundle. 

It's simple. And I like it.

Status
------
This bundle is under development and may break.

Storage Layers
--------------
- Doctrine Mongodb

Installation
------------
This bundle is listed on packagist.

Simply add it to your apps composer.json file

``` js
    "avro/blog-bundle": "*"
```

Enable the bundle in the kernel:

``` php
// app/AppKernel.php
    new Avro\BlogBundle\AvroBlogBundle(),
```

Configuration
-------------
Add this required config to your app/config/config.yml file

``` yaml
avro_blog:
    db_driver: mongodb # supports mongodb
    list_count: 10 # The number of posts to show on the blog roll
```

Add routes to your app/config/routing.yml file

``` yaml
AvroBlogBundle:
    resource: "@AvroBlogBundle/Resources/config/routing/routing.yml"
```

