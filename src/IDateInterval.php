<?php

namespace bg13\entities;

use \DateTime;
use \InvalidArgumentException;


/**
 * Represents interval that has a start date and an end date. Both dates can be
 * null to represent an open interval. The implementation must prevent both
 * dates to be null at the same time.
 *
 * All date strings must be of format YYYY-mm-dd
 */
interface IDateInterval {
    
    /**
     * @return DateTime The start date
     */
    function getStart() : DateTime;
    
    /**
     * @return DateTime|null The end (optional, null if open interval)
     */
    function getEnd();
    
    
    /**
     * @return string|null The start (optional, null if open interval)
     */
    function getStartStr();
    
    /**
     * @return string|null The end (optional, null if open interval)
     */
    function getEndStr();
    
    /**
     * Sets the new start date.
     *
     * @param string|null $start The new start date or null if the lower time
     *                           border is open.
     *
     * @throws InvalidArgumentException If the new start date is null but the
     *                                  current end date is already null.
     * @throws InvalidArgumentException If the new start date is higher than the
     *                                  current end date.
     */
    function setStartStr($start);
    
    /**
     * Sets the new end date.
     *
     * @param string|null $end The new end date or null if the upper time border
     *                         is open.
     *
     * @throws InvalidArgumentException If the new end date is null but the
     *                                  current start date is already null.
     * @throws InvalidArgumentException If the new end date is lower than the
     *                                  current end date.
     */
    function setEndStr($end);
    
    /**
     * @return bool True if this date time interval is current
     */
    function isCurrent() : bool;
    
    /**
     * Checks if a datetime is contained in this interval
     *
     * @param string $date The date to check for
     * @return bool True if it is contained
     */
    function contains(string $date) : bool;
    
    /**
     * Checks if a date interval is fully contained within this one.
     *
     * @param IDateInterval $interval The interval to check for
     * @return bool True if it is fully contained
     */
    function containsInterval(IDateInterval $interval) : bool;
    
    /**
     * @param string $empty If a time border is open (null) this placeholder is
     *                      inserted.
     * @return string String representation of the date interval in local format
     *                "[from; to]"
     */
    function toIntervalString(string $empty='?') : string;
    
    /**
     * Checks if this data interal overlaps with another one.
     *
     * @param IDateInterval $interval The date interval to check.
     * @return bool True, if overlapping
     */
    function overlapsWith(IDateInterval $interval) : bool;
    
}