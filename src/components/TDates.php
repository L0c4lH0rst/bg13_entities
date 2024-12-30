<?php

namespace bg13\entities\components;

use bg13\entities\models\IDateInterval;
use DateTime;
use \InvalidArgumentException;


/**
 * Encapsulates all functionlities for handling dates and date intervals to
 * avoid redundance in multiple implementations of IDateInterval.
 */
trait TDates {
    
    /**
     * Checks if a new start date can be set to a date interval. If this
     * function does not throw any exception, the new start date is valid. See
     * the references interface method for all throwables.
     *
     * @param string|null $start The new start date to set as lower border of
     *                           a date interval. Can be null to indicate an
     *                           open date interval.
     * @param IDateInterval $dateInterval The date interval that should be
     *                                    modified
     *
     * @see \bg13\entities\models\IDateInterval::setStartStr()
     */
    protected function checkStartStr($start, IDateInterval $dateInterval) {
        if(is_null($start)) {
            // prevent an infinite date interval
            if(is_null($dateInterval->getEndStr())) {
                throw new InvalidArgumentException(
                    'The lower date border of this interval cannot be removed '.
                    'if the upper date border is already open.'
                );
            }
        } else {
            // prevent inconsistent date interval
            if(!is_null($dateInterval->getEndStr())) {
                throw new InvalidArgumentException(
                    'The lower date border of this interval cannot be greater '.
                    'than upper date border.'
                );
            }
        }
    }
    
    
    /**
     * Checks if a new end date can be set to a date interval. If this
     * function does not throw any exception, the new end date is valid. See
     * the references interface method for all throwables.
     *
     * @param string|null $end The new end date to set as upper border of
     *                         a date interval. Can be null to indicate an
     *                         open date interval.
     * @param IDateInterval $dateInterval The date interval that should be
     *                                    modified
     *
     * @see \bg13\entities\models\IDateInterval::setEndStr()
     */
    protected function checkEndStr($end, IDateInterval $dateInterval) {
        if(is_null($end)) {
            // prevent an infinite date interval
            if(is_null($dateInterval->getStartStr())) {
                throw new InvalidArgumentException(
                    'The upper date border of this interval cannot be removed '.
                    'if the lower date border is already open.'
                );
            }
        } else {
            // prevent inconsistent date interval
            if(!is_null($dateInterval->getStartStr())) {
                throw new InvalidArgumentException(
                    'The upper date border of this interval cannot be lower '.
                    'than lower date border.'
                );
            }
        }
    }
    

    /**
     * Checks if a datetime lies within a datetime interval
     *
     * @param IDateInterval $di The interval to check against
     * @param string $date The datetime (string: YYYY-mm-dd HH:ii:ss) to check
     *
     * @return bool True if it is contained
     */
    protected function intervalContainsDate(IDateInterval $di, string $date)
        : bool
    {
        // open lower border, just check against the upper date border
        if(is_null($di->getStartStr())) {
            return $date <= $di->getEndStr();
        }
        
        // open upper border, just check against the lower date border
        if(is_null($di->getEndStr())) {
            return $date >= $di->getStartStr();
        }
        
        // closed interval, check against both borders
        return $date >= $di->getStartStr() && $date <= $di->getEndStr();
    }
    
    
    /**
     * @return DateTime The current timestamp
     */
    protected function now() : DateTime {
        return new DateTime();
    }
    
    
    /**
     * Converts DateTime object to string in us_en format.
     *
     * @param DateTime $dt Object to convert
     * @return string Converted date time string
     */
    protected function getAsDateString(DateTime $dt) : string {
        return $dt->format('Y-m-d H:i:s');
    }
    
    
    /**
     * Checks if a date time interval is 'current', which means it encloses the
     * current date and time.
     *
     * @param IDateInterval $di The interval to check
     * @return bool True if current, false if it lies in the future or past
     */
    protected function intervalIsCurrent(IDateInterval $di) : bool {
        return $this->intervalContainsDate(
            $di, $this->getAsDateString($this->now())
        );
    }
    
    
    /**
     * Checks if a date interval is fully contained within in another one.
     *
     * @param IDateInterval $intervalOuter The interval that should contain
     *                                     another one
     * @param IDateInterval $intervalInner The interval to should be contained
     *                                     in another one
     *
     * @return bool True if it is fully contained. If the inner interval is open
     *              it is only considered 'contained' if the outer one is also
     *              open (otherwhise a part of the inner interval would lie
     *              outside of the outer one).
     */
    protected function intervalContainsInterval(
        IDateInterval $intervalOuter, IDateInterval $intervalInner
    ) : bool {
        // shortcuts
        $sOut = $intervalOuter->getStart();
        $eOut = $intervalOuter->getEnd();
        $sIn = $intervalInner->getStart();
        $eIn = $intervalInner->getEnd();
        
        // @todo does not work with lower open borders yet
        
        if($sIn >= $sOut && (is_null($eOut) || $sIn <= $eOut)) {
            // the inner start date must lie within the outer start and end
            if(is_null($eOut)) {
                // if the outer interval is open, it is contained anyway
                return true;
            } else {
                // if the outer interval is closed the inner end date has to
                // be checked further
                if(is_null($eIn)) {
                    // if the inner end is open the outer has to be open too
                    return is_null($eOut);
                } else {
                    // if the inner interval is closed, it is contained in the
                    // outer one, if that one is open or it's end lies after
                    // the inner end
                    return is_null($eOut) || $eIn <= $eOut;
                }
            }
        } else {
            // inner start date lies outside of the outer date limits, so it
            // cannot be contained
            return false;
        }
    }
    
}