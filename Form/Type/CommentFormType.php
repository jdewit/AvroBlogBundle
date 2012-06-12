<?php
namespace Avro\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/*
 * Comment Form Type
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class CommentFormType extends AbstractType
{ 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('post', 'entity', array(
                'empty_value' => 'Select a post...',
                'label' => 'Post',
                'required' => false,
                'class' =>'Avro\BlogBundle\Entity\Post',
                'attr' => array(
                    'title' => 'Choose a post',  
                    'class' => '',
                )
            ))  
            ->add('body', 'textarea', array(
                'label' => 'Body',
                'required' => false,
                'attr' => array(
                    'title' => 'Enter the body',  
                    'class' => '',
                )
            ))          
            ->add('name', 'text', array(
                'label' => 'Name',
                'required' => false,
                'attr' => array(
                    'title' => 'Enter the name',  
                    'class' => 'capitalize',
                )
            ))   
  
            ->add('isApproved', 'checkbox', array(
                'label' => 'Is Approved',
                'required' => false,
                'attr' => array(
                    'title' => 'Is Approved?',  
                )
            ))   

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avro\BlogBundle\Entity\Comment'
        ));
    }

    public function getName()
    {
        return 'avro_blog_comment';
    }
}
