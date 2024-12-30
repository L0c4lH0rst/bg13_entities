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
    
    
    /**
     * Using classes have to define the creation of an formatter
     *
     * @return IFormatter A central formatter service
     */
    abstract protected function getFormatter() : IFormatter;
    

    /**
     * Converts date string from internal default format (en_US = YYYY-mm-dd)
     * into the current default format that is set to the central formatter
     * service (e.g. to realize localization).
     *
     * @param string $date The date to localize
     * @return string Localized date or null if original value was invalid
     */
    protected function convertAsDate(string $date) {
        return $this->getFormatter()->asDate($date);
    }
    
    
    /**
     * Generates human-readable date interval string
     *
     * @param string $start Start of the date interval
     * @param string|null $end Optional end of the date interval
     * @param string $empty If a end is open (null) this placeholder is used.
     *
     * @return string String representation of the date interval in local format
     *                "[from; to]"
     */
    protected function convertDatesToIntervalString(
        string $start, $end, string $empty='?'
    ) : string {
        return '['.$this->convertAsDate($start).'; '.
            (is_null(($end)) ? $empty :$this->convertAsDate(($end))).
            ']';
    }
    
    
    /**
     * Checks if two date intervals overlap. Both intervals may have an open
     * end date.
     *
     * @param IDateInterval $interval1 Interval 1
     * @param IDateInterval $interval2 Interval 2
     *
     * @return bool True, if overlapping
     */
    function doIntervalsOverlap(
        IDateInterval $interval1, IDateInterval $interval2
    ) : bool {
        // shortcuts
        $s1 = $interval1->getStartStr();
        $e1 = $interval1->getEndStr();
        $s2 = $interval2->getStartStr();
        $e2 = $interval2->getEndStr();
        
        // if both intervals have open ends they must overlap
        if(is_null($e1) && is_null($e2)) {
            return true;
        }
        
        // if interval 1 is open, but not interval 2
        if(is_null($e1) && !is_null($e2)) {
            if($s1 <= $e2) {
                return true;
            }
        }
        
        // if interval 2 is open, but not interval 1
        if(!is_null($e1) && is_null($e2)) {
            if($s2 <= $e1) {
                return true;
            }
        }
        
        // after this point, both intervals have to be closed
        if(!is_null($e1) && !is_null($e2)) {
            
            // interval 1 starts or ends within interval 2
            if(
                $s1 >= $s2 && $s1 <= $e2 ||
                $e1 >= $s2 && $e1 <= $e2
            ) {
                return true;
            }
                
            // interval 2 starts or ends within interval 1
            if(
                $s2 >= $s1 && $s2 <= $e1 ||
                $e2 >= $s1 && $e2 <= $e1
            ) {
                return true;
            }
                
            // interval 1 encapsulates interval 2
            if(
                $s2 >= $s1 && $s2 <= $e1 &&
                $e2 >= $s1 && $e2 <= $e1
            ) {
                return true;
            }
            
            // interval 2 encapsulates interval 1
            if(
                $s1 >= $s2 && $s1 <= $e2 &&
                $e1 >= $s2 && $e1 <= $e2
            ) {
                return true;
            }
                            
        }
        
        // no overlap, on interval ends before the other one starts
        return false;
    }


}