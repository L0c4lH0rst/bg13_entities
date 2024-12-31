<?php
namespace src\components;


/**
 * Interface for a translator componenet that implements i18n.
 */
interface ITranslator {
    
    /**
     * Translates a message into from the given source language to the given
     * target language.
     *
     * @param string $msg The message to translate (may have placeholders that
     *                    can be set via the $params parameter)
     * @param string $category A category to separate different translations
     *                         for the same text (according to i18n)
     * @param array $params Optional parameters to be injected into the message
     *                      that won't get translated.
     *
     * @return string The translated string
     */
    public function getC(string $msg, string $category, array $params=[])
        : string;
    
    /**
     * Translates a messages assuming the i18n category 'app'. Should be used
     * for general text, like menus, descriptions, ...
     *
     * @see ITranslator::getC() for details
     */
    public function get(string $msg, array $params=[]) : string;
    
    /**
     * Translates a messages assuming the i18n category 'attr'. Should be used
     * for attribute names of entities.
     *
     * @see ITranslator::getC() for details
     */
    public function getA(string $msg, array $params=[]) : string;
    
    /**
     * Translates a messages assuming the i18n category 'err'. Should be used
     * for any error messages or warnings.
     *
     * @see ITranslator::getC() for details
     */
    public function getE(string $msg, array $params=[]) : string;
    
}

