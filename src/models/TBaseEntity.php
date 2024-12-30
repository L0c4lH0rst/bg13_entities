<?php
namespace bg13\entities;

use \TypeError;


/**
 * Default implementation of IBaseEntity.
 *
 * This can be included in entity classes that implement IBaseEntity but may be
 * necessarily derived from any framework classes (like an ActiveRecord or a
 * Model). Therefore many of the public functions of this trait are compatible
 * with IBaseEntity. Some functions of this trait also call functions of the
 * IBaseEntity interface which have to be implemented in the final entity class.
 *
 * It also provides additional functionalities for attribute handling. This
 * trait assumes that it has direct access to attributes via
 * $this->attributename.
 */
trait TBaseEntity {
    
    /**
    * {@inheritDoc}
    * @see \bg13\entities\IBaseEntity::getEntityClassNamePath()
    */
    public function getEntityClassNamePath(bool $withPath=true) : string {
        $cn = $this->getEntityClassName();
        
        if($withPath) {
            return $cn;
        } else {
            $split = explode("\\", $cn);
            return $split[count($split) - 1];
        }
    }
    
    
    /**
     * By default, this function assumes an attribute 'name' to use as the
     * display name of this entity.
     *
     * @see IBaseEntity::getDisplayName()
     *
     * @throws TypeError If the attribute 'name' is not convertable to a string
     */
    public function getDisplayName() : string {
        return $this->name;
    }
    
    
    /**
     * Corrects user input. If the user leaves an field empty the corresponding
     * attribute often should be set to NULL. It could by default be set to
     * empty string or to '0'. By applying this function on such attributes the
     * resulting NULL is assured.
     *
     * @param string $attrName The attribute to check and to correct
     * @param bool $also0 If false only empty strings will result in NULL. If
     *                    true also the a string with a digit zero '0' counts as
     *                    empty.
     */
    protected function ensureNullIfEmpty(string $attrName, bool $also0=false) {
        if('' == $this->$attrName) {
            $this->$attrName = null;
        }
        
        if($also0 && '0' == $this->$attrName) {
            $this->$attrName = null;
        }
    }
    
    
    /**
     * The inverse function of ensureNullIfEmpty: If a field is NULL it will be
     * set to zero.
     *
     * @param string $attrName The attribute to check and to correct
     */
    protected function ensure0IfNull(string $attrName) {
        if(is_null($this->$attrName)) {
            $this->$attrName = 0;
        }
    }
    
}
