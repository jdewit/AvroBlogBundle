<?php
namespace Avro\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Avro\AssetBundle\Form\Type\ImageFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ->add('tags', 'entity', array(
                'class' => 'Avro\BlogBundle\Entity\Tag',
                'multiple' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avro\BlogBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'avro_blog_post';
    }
}
