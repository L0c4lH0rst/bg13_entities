<?php
namespace bg13\entities;


/**
 * All entity classes have to implement this basic interface, providing the
 * functionality to tell about the class, the name, errors, ...
 */
interface IBaseEntity {
    
    /**
     * @return string The display name of the entity object
     */
    function getDisplayName() : string;
    
}