<?php

namespace Byteam\Spectre\Exceptions;


use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpJsonException extends HttpException
{
    /**
     * @var array
     */
    private $data;

    public function __construct($statusCode, array $data, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}