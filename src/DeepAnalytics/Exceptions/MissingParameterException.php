<?php

    namespace DeepAnalytics\Exceptions;

    use Exception;
    use Throwable;

    class MissingParameterException extends Exception
    {
        /**
         * @param string $parameter
         * @param int $error_code
         * @param Throwable|null $previous
         */
        public function __construct(string $parameter, int $error_code, Throwable $previous = null)
        {
            parent::__construct('The parameter ' . $parameter . ' is missing', $error_code, $previous);
        }
    }