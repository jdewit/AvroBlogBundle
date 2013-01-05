<?php
namespace Avro\PaginatorBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;

class AvroPaginatorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) {

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('mongodb.xml');
        $loader->load('twig.xml');

        $container->setParameter('avro_paginator.options', $config['paginators']);
    }
}

