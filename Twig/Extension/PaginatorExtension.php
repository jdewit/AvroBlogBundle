<?php

namespace Avro\PaginatorBundle\Twig\Extension;

use Avro\PaginatorBundle\Paginator\PaginatorInterface;

/*
 * Paginator Extension
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class PaginatorExtension extends \Twig_Extension
{
    private $environment;
    private $parameters;

    /**
     * @param \Twig_Environment $environment
     * @param CacheManager $cachManager
     * @param array $carouselParameters
     */
    public function __construct(\Twig_Environment $environment, $parameters = array())
    {
        $this->environment = $environment;
        $this->parameters = $parameters;
    }

    public function getFunctions()
    {
        return array(
            'paginator' => new \Twig_Function_Method($this, 'renderPaginator', array('is_safe' => array('html'))),
            'pager' => new \Twig_Function_Method($this, 'renderPager', array('is_safe' => array('html'))),
            'paginator_heading' => new \Twig_Function_Method($this, 'renderHeading', array('is_safe' => array('html'))),
            'paginator_filter' => new \Twig_Function_Method($this, 'renderFilter', array('is_safe' => array('html'))),
            'paginator_limit' => new \Twig_Function_Method($this, 'renderLimit', array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders a paginator.
     *
     * @param string $name
     * @param Document $object
     * @param array $options
     *
     * @return string
     */
    public function renderPaginator($paginator, array $options = array())
    {
        $options = array_merge($paginator->getOptions(), $options);

        if ($options['lastPage'] == 1) {
            return false;
        }

        return $this->environment->render($options['paginatorTemplate'], $options);
    }

    /**
     * Renders a pager.
     *
     * @param Paginator
     * @param array $options
     *
     * @return string
     */
    public function renderPager(PaginatorInterface $paginator, array $options = array())
    {
        $options = array_merge($paginator->getOptions(), $options);

        return $this->environment->render($options['pagerTemplate'], $options);
    }

    public function renderHeading(PaginatorInterface $paginator, $field, array $options = array())
    {
        $options = array_merge($paginator->getOptions(), $options);
        $options['field'] = $field;
        $options['routeParams']['sort'] = $field;

        if ($options['routeParams']['direction'] == 'asc') {
            $options['routeParams']['direction'] = 'desc';
        } else {
            $options['routeParams']['direction'] = 'asc';
        }

        return $this->environment->render($options['headingTemplate'], $options);
    }

    public function renderFilter($paginator, array $options = array())
    {
        $options = array_merge($paginator->getOptions(), $options);

        return $this->environment->render($options['filterTemplate'], $options);
    }

    public function renderLimit(PaginatorInterface $paginator, array $options = array())
    {
        $options = array_merge($paginator->getOptions(), $options);

        return $this->environment->render($options['limitTemplate'], $options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avro_paginator';
    }
}
