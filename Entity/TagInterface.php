<?php
namespace Avro\BlogBundle\Entity;

/*
 * @author Joris de <joris.w.dewit@gmail.com>
 */
interface TagInterface
{
    
    function getId();
    
/**
     * Get name
     * 
     * @return string 
     */
    function getName();
    
    /**
     * Set name
     *
     * @param string $name
     */
    function setName($name);
       
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
       

}
