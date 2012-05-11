<?php
namespace Avro\BlogBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    private $factory;
    private $context;
    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, $context)
    {
        $this->factory = $factory;
        $this->context = $context;
    }

    public function createSubMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri());
        $menu->addChild('Blogs', array('route' => 'avro_blog_post_list'));
        $menu['Blogs']->addChild('Featured', array('route' => 'avro_blog_post_list_featured'));
        $menu['Blogs']->addChild('Latest', array('route' => 'avro_blog_post_list_latest', array('filter' => 'latest')));
        
        if ($this->context->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Upload', array('route' => 'avro_blog_post_new'));
        }
        return $menu;
    }
}
