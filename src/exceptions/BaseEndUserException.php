<?php
namespace bg13\entities\exceptions;

use \Exception;
use Throwable;
use src\components\ITranslator;


/**
 * An exception that is ment to be shown to the user directly. Therefore the
 * message has to be human-readable.
 */
abstract class BaseEndUserException extends Exception {
    
    /**
     * Default constructor that takes a message with all information for a
     * translation (assuming category error).
     *
     * @param string $message The error message to translate
     * @param array $params Optional translation parameters
     * @param int $code Optional exception code
     * @param Throwable $previous Optional exception chaining
     */
    public function __construct(
        string $message, array $params=[],
        int $code = null, ?Throwable $previous = null
    ) {
        parent::__construct(
            $this->getTranslator()->getE($message, $params),
            $code, $previous
        );
    }
    
    
    /**
     * @return ITranslator
     */
    abstract protected function getTranslator();
    
}