<?php
namespace Avro\BlogBundle\Entity;

/*
 * @author Joris de <joris.w.dewit@gmail.com>
 */
interface PostInterface
{
    
    function getId();
    
    /**
     * Get title
     * 
     * @return string 
     */
    function getTitle();
    
    /**
     * Set title
     *
     * @param string $title
     */
    function setTitle($title);
       
    /**
     * Get abstract
     * 
     * @return text 
     */
    function getAbstract();
    
    /**
     * Set abstract
     *
     * @param text $abstract
     */
    function setAbstract($abstract);
       
    /**
     * Get content
     * 
     * @return text 
     */
    function getContent();
    
    /**
     * Set content
     *
     * @param text $content
     */
    function setContent($content);
       
    /**
     * Get enabled
     * 
     * @return boolean 
     */
    function getEnabled();
    
    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    function setEnabled($enabled);
       
    /**
     * Get slug
     * 
     * @return string 
     */
    function getSlug();
    
    /**
     * Set slug
     *
     * @param string $slug
     */
    function setSlug($slug);
       
    /**
     * Get publicationDateStart
     * 
     * @return datetime 
     */
    function getPublicationDateStart();
    
    /**
     * Set publicationDateStart
     *
     * @param datetime $publicationDateStart
     */
    function setPublicationDateStart($publicationDateStart);
       
    /**
     * Get views
     * 
     * @return string 
     */
    function getViews();
    
    /**
     * Set views
     *
     * @param string $views
     */
    function setViews($views);
       
    /**
     * Get tag
     * 
     * @return Avro\BlogBundle\Entity\Tag 
     */
    function getTags();

    /**
     * Set tags
     *
     * @param manyToMany $tags
     */
    function setTags(\Avro\BlogBundle\Entity\TagInterface $tags);
    
    /**
     * Add tag
     */
    function addTag(\Avro\BlogBundle\Entity\TagInterface $tag);
    
    /**
     * Remove tag
     */
    function removeTag(\Avro\BlogBundle\Entity\TagInterface $tag);
       
    /**
     * Get createdBy
     * 
     * @return Avro\UserBundle\Entity\User 
     */
    function getCreatedBy();

    /**
     * Set createdBy
     *
     * @param manyToOne $createdBy
     */
    function setCreatedBy(\Avro\UserBundle\Entity\User $createdBy);    
       
    /**
     * Get isFeatured
     * 
     * @return boolean 
     */
    function getIsFeatured();
    
    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     */
    function setIsFeatured($isFeatured);
       

}
