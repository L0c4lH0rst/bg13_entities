<?php
namespace bg13\entities\components;


/**
 * Interface for a formating component.
 */
interface IFormatter {
    
    /**
     * Formats date string into localized target format
     *
     * @param string $date The date to format
     * @param string|null $locale The target format or null for the default
     *                            format
     * @return string formatted (localized) date string
     */
    function asDate(string $date, string $locale=null);
    
}