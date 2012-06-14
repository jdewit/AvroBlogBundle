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
            ->add('body', 'textarea', array(
                'label' => 'Body',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Your comment',
                    'title' => 'Enter a comment',
                    'class' => 'span9',
                )
            ))
            ->add('name', 'text', array(
                'label' => 'Name',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Your name',
                    'title' => 'Enter your name',
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
