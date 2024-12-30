<?php

namespace bg13\entities\repositories;

use bg13\entities\models\IBaseEntity;


/**
 * Base class for all entity repositories used for loading and saving.
 */
abstract class BaseEntityRepository {
    
    /**
     * @var IBaseEntity Reserved as 'singleton' for getEntityInstance().
     */
    protected $entity;
    
    
    /**
     * Initialization of repository: creates instance of managed entity
     */
    public function __construct() {
        $this->entity = $this->createNewInstance();
    }
    

    
    /**
     * @return IBaseEntity A default entity instance
     */
    public function getEntityInstance() {
        return $this->entity;
    }
    
    
    /**
     * @return IBaseEntity New empty entity instance
     */
    abstract public function createNewInstance();
    
    
    /**
     * Load one specific entity by filter.
     *
     * @param array $attributes search conditions for every attribute as key-
     *                          value pairs where the key is the attribute name
     *                          and the value is the expression to search for.
     *
     * @return IBaseEntity|null The entity or null if none found
     */
    abstract public function load(array $attributes);
    
    
    /**
     * Load mulitiple entities by filter.
     *
     * @param array $attributes search conditions for every attribute as key-
     *                          value pairs where the key is the attribute name
     *                          and the value is the expression to search for.
     * @return IBaseEntity[] All found entities
     */
    abstract public function loadAll(array $attributes) : array;
    
    
    /**
     * Tries to persist an entity.
     *
     * @param IBaseEntity &$entity The entity to persist, passed by reference to
     *                             return the attribute errors in the entity.
     * @return boolean True if successful, false if failed
     */
    abstract public function save(IBaseEntity $entity);
    
    
    /**
     * Tries to delete an existing entity by it's primary key.
     *
     * @param EntityPK $id The primary key of the entity to delete
     * @return int Number of entities that were successfully deleted.
     */
    abstract public function deleteAll(EntityPK $id);
    
    
    /**
     * Tries to load all entities who's primary keys do not match a given list
     * of values.
     *
     * @param EntityPK[] $pks A list of primary key values that are to exclude
     *                        while loading.
     * @return IBaseEntity[] All entities that match the exclusion filter
     */
    abstract public function loadAllByPKExclusive(array $pks);
    
}