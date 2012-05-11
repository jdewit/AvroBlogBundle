<?php
namespace Avro\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TagFormType extends AbstractType
{ 
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => ' Name',
                'attr' => array(
                    'title' => 'Enter the name for the tag'  
                )
            ))          
  
            ->add('enabled', 'checkbox', array(
                   'label' => ' Enabled',
            ))   
            ->add('slug', 'text', array(
                'label' => ' Slug',
                'attr' => array(
                    'title' => 'Enter the slug for the tag'  
                )
            ))          

        ;
    }

    public function getDefaultOptions()
    {
        return array('data_class' => 'Avro\BlogBundle\Entity\Tag');
    }    
    
    public function getName()
    {
        return 'avro_blog_tag';
    }
}
