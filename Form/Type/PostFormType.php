<?php
namespace Avro\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Avro\AssetBundle\Form\Type\ImageFormType;

class PostFormType extends AbstractType
{ 
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => false,
                'attr' => array(
                    'title' => 'Enter the title for the post',
                    'class' => 'span12'  
                )
            ))          
         
            ->add('content', 'textarea', array(
                'required' => false,
                'attr' => array(
                    'title' => 'Enter the content for the post',
                    'class' => 'span12',
                    'style' => 'min-height: 20em;'
                )
            )) 
        ;
    }

    public function getDefaultOptions()
    {
        return array('data_class' => 'Avro\BlogBundle\Entity\Post');
    }    
    
    public function getName()
    {
        return 'avro_blog_post';
    }
}
