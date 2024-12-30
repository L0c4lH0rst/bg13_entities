<?php

namespace bg13\entities\models;


/**
 * All entity classes have to implement this basic interface, providing the
 * functionality to tell about the class, the name, errors, ...
 */
interface IBaseEntity {
    
    /**
     * @return string The PHP name of the entity class
     */
    function getEntityClassName() : string;
 
    /**
     * Again provides the class name with the option to leave out the class
     * path.
     *
     * @param bool $withPath If true that path is included
     * @return string The class name
     */
    function getEntityClassNamePath(bool $withPath=true) : string;
    
    
    /**
     * @return string The displayname of this entity
     */
    function getEntityClassDisaplyName() : string;
    
    /**
     * @return string The displayname of the entity class (plural)
     */
    function getEntityClassDisaplyNamePlural() : string;
    
    /**
     * @return string The display name of the entity object
     */
    function getDisplayName() : string;
    
    /**
     * @return string Total validation error summary in one string
     */
    function getErrorSummaryString() : string;
    
}