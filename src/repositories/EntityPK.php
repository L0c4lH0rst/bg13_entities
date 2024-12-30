<?php

namespace bg13\entities\repositories;


/**
 * Primary key of a IBaseEntity. Can be a single value or a multi value key.
 */
class EntityPK {
    
    /**
     * @var array Multi-value primary key, where the array keys are the names of
     *            the attributes and the values their values.
     */
    protected $multiKey;
    
    
    /**
     * Create new Entity primary key.
     *
     * @param array $multiKey Multi-value primary key, where the array keys are
     *                        the names of the attributes and the values their
     *                        values.
     */
    private function __construct(array $multiKey) {
        $this->multiKey = $multiKey;
    }
    
    
    /**
     * Create single-value primary key.
     *
     * @param mixed $value A primitve value as primary key
     * @param string $attribute The attribute of the entity that stores the
     *                          primary key, by default 'id'
     *
     * @return EntityPK The single-value primary key
     */
    public static function get($value, $attribute='id') : EntityPK {
        return new EntityPK([$attribute => $value]);
    }
    
    
    /**
     * Multi-value primary key
     *
     * @param array $multiKey Multi-value primary key, where the array keys are
     *                        the names of the attributes and the values their
     *                        values.
     * @return EntityPK The multi-value primary key
     */
    public static function getMulti(array $multiKey) : EntityPK {
        return EntityPK($multiKey);
    }
    
    
    /**
     * @return array Multi-value primary key, where the array keys are the names
     *               of the attributes and the values their values.
     */
    public function getKey() : array {
        return $this->multiKey;
    }


}