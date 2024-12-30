<?php

namespace bg13\entities;


/**
 * Default implementation of IDateInterval and IBaseEntity (because it includes
 * TBaseEntity). This trait should be included in entity classes that
 * 1.) represent date intervals 2.) implement IBaseEntity and 3.) may be
 * derived from framework depended classes.
 *
 * Therefore many of the public functions of this trait are compatible with
 * IDateInterval.
 */
trait TBaseEntityDateInterval {
    
    /**
     * To cover entity functionality
     */
    use TBaseEntity;
    
    /**
     * To cover data(time) handling
     */
    use TDates;
    
    
    /**
     * Assumtion: this entity contains a non-null string attribute with the name
     * 'date_start'.
     *
     * {@inheritDoc}
     * @see \bg13\entities\IDateInterval::getStartStr()
     */
    public function getStartStr() {
        return $this->date_start;
    }
    
    
    /**
     * Assumtion: this entity contains a non-null string attribute with the name
     * 'date_end'.
     *
     * {@inheritDoc}
     * @see \bg13\entities\IDateInterval::getEndStr()
     */
    public function getEndStr() {
        return $this->date_end;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\IDateInterval::setStartStr()
     */
    public function setStartStr($start) {
        $this->checkStartStr($start, $this);
        $this->date_start = $start;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\IDateInterval::setEndStr()
     */
    public function setEndStr($end) {
        $this->checkEndStr($end, $this);
        $this->date_end = $end;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\IDateInterval::isCurrent()
     */
    public function isCurrent() : bool {
        return $this->intervalIsCurrent($this);
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\IDateInterval::contains()
     */
    public function contains(string $date) : bool {
        return $this->intervalContains($date);
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\IDateInterval::containsInterval()
     */
    public function containsInterval(IDateInterval $interval) : bool {
        return $this->intervalContainsInterval($this, $interval);
    }
    
}