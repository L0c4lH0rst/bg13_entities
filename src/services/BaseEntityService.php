<?php

namespace bg13\entities\services;

use bg13\entities\repositories\BaseEntityRepository;


/**
 * Base class for all services that hold business logic related to a specific
 * entity (based an BaseEntity)
 */
abstract class BaseEntityService {
    
    
    /**
     * @var BaseEntityRepository Repository, related to the entity class that is
     *                           managed by this service.
     */
    protected $repository;
    
    /**
     * @return BaseEntityRepository Sub classes can define which non-abstract
     *                              repository to create for this service.
     */
    abstract protected function createRepository();
    
    
    /**
     * @return BaseEntityRepository Access to single instance of repository
     */
    public function getRepository() {
        if(is_null($this->repository)) {
            $this->repository = $this->createRepository();
        }
        return $this->repository;
    }
    
    
    /**
     * Tries to load an entity by attribute based search.
     * 
     * @param array $filter Attribute based filter as key-value pairs, where the
     *                      key is the attribute name
     * @return \bg13\entities\models\IBaseEntity|NULL
     */
    public function loadOne(array $filter) {
        // @todo
    }
    
    
}