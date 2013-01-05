<?php
namespace Avro\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/*
 * Tag Form Type
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class TagFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('category', 'document', array(
                //'label' => 'Category',
                //'class' =>'Avro\BlogBundle\Document\Category',
                //'attr' => array(
                    //'title' => 'Select your category',
                     //'class' => 'add-option',
                     //'data-text' => 'Create a new category',
                     //'data-route' => 'avro_blog_category_new',
                //)
            //))

            ->add('name', 'text', array(
                'label' => 'Name',
                'required' => false,
                'attr' => array(
                    'title' => 'Enter the name',
                    'class' => 'capitalize',
                )
            ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avro\BlogBundle\Document\Tag'
        ));
    }

    public function getName()
    {
        return 'application_blog_tag';
    }
}
