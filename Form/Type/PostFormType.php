<?php
namespace Avro\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Avro\ImageBundle\Document\Image;
use Avro\ImageBundle\Form\Type\ImageFormType;

/*
 * Post Form Type
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class PostFormType extends AbstractType
{

    protected $section;

    function __construct($section)
    {
        $this->section = $section;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($this->section) {
            case 'new':
                $builder
                    ->add('createdBy', 'purified_text', array(
                        'label' => 'Author',
                        'required' => false,
                        'attr' => array(
                            'title' => 'Enter the author',
                            'class' => 'input-xxlarge',
                        )
                    ))

                    ->add('title', 'purified_text', array(
                        'label' => 'Title',
                        'required' => false,
                        'attr' => array(
                            'title' => 'Enter the title',
                            'class' => 'input-xxlarge',
                        )
                    ))
                    ->add('summary', 'purified_textarea', array(
                        'label' => 'Summary',
                        'required' => false,
                        'attr' => array(
                            'maxlength' => 125,
                            'title' => 'Enter the summary',
                            'class' => 'input-xxlarge',
                        )
                    ))
                    ->add('image', new ImageFormType(true), array(
                        'label' => 'Image'
                    ))
                ;
            break;
            case 'title':
                $builder
                    ->add('title', 'purified_text', array(
                        'label' => 'Title',
                        'required' => false,
                        'attr' => array(
                            'title' => 'Enter the title',
                            'class' => 'input-xxlarge',
                        )
                    ))
                    ->add('summary', 'purified_textarea', array(
                        'label' => 'Summary',
                        'required' => false,
                        'attr' => array(
                            'maxlength' => 125,
                            'title' => 'Enter the summary',
                            'class' => 'input-xxlarge',
                        )
                    ))
                ;
            break;
            case 'content':
                $builder
                    ->add('content', 'purified_textarea', array(
                        'label' => 'Content',
                        'required' => false,
                        'attr' => array(
                            'title' => 'Enter the content',
                            'class' => 'tinymce',
                        )
                    ))
                ;
            break;
            case 'image':
                $builder
                    ->add('image', new ImageFormType(true), array(
                        'label' => 'Image'
                    ))
                ;
            break;

            case 'tags':
                $builder
                    ->add('tags', 'document', array(
                        'label' => 'Tags',
                        'class' => 'Avro\BlogBundle\Document\Tag',
                        'multiple' => true,
                        'error_bubbling' => true,
                        'query_builder' => function($repo) {
                            return $repo->createQueryBuilder()
                                ->sort('name', 'asc');
                        },
                        'attr' => array(
                            'class' => 'add-option',
                            'data-text' => 'Create a new tag',
                            'data-route' => 'avro_blog_tag_new',
                        )
                    ))
                ;
            break;
            case 'admin':
                $builder
                    ->add('isApproved', 'checkbox', array(
                        'label' => 'Is Approved',
                        'required' => false,
                        'attr' => array(
                            'title' => 'Is Approved?',
                        )
                    ))

                    ->add('isFeatured', 'checkbox', array(
                        'label' => 'Is Featured',
                        'required' => false,
                        'attr' => array(
                            'title' => 'Is Featured?',
                        )
                    ))
                ;
            break;
        }


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avro\BlogBundle\Document\Post'
        ));
    }

    public function getName()
    {
        return 'avro_blog_post';
    }
}
