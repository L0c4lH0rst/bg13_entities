<?php

namespace bg13\entities\models;

use bg13\entities\components\TDates;


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
     * @see \bg13\entities\models\IDateInterval::getStartStr()
     */
    public function getStartStr() {
        return $this->date_start;
    }
    
    
    /**
     * Assumtion: this entity contains a non-null string attribute with the name
     * 'date_end'.
     *
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::getEndStr()
     */
    public function getEndStr() {
        return $this->date_end;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::setStartStr()
     */
    public function setStartStr($start) {
        $this->checkStartStr($start, $this);
        $this->date_start = $start;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::setEndStr()
     */
    public function setEndStr($end) {
        $this->checkEndStr($end, $this);
        $this->date_end = $end;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::isCurrent()
     */
    public function isCurrent() : bool {
        return $this->intervalIsCurrent($this);
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::contains()
     */
    public function contains(string $date) : bool {
        return $this->intervalContains($date);
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::containsInterval()
     */
    public function containsInterval(IDateInterval $interval) : bool {
        return $this->intervalContainsInterval($this, $interval);
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::toIntervalString()
     */
    public function toIntervalString(string $empty='?') : string {
        return $this->convertDatesToIntervalString(
            $this->getStartStr(), $this->getEndStr(), $empty
        );
    }
    
    
    /**
     * {@inheritDoc}
     * @see \bg13\entities\models\IDateInterval::overlapsWith()
     */
    function overlapsWith(IDateInterval $interval) : bool {
        return $this->doIntervalsOverlap($this, $interval);
    }
    
}