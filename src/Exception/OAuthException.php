<?php

namespace Byteam\Spectre\Exception;


use League\OAuth2\Server\Exception\OAuthServerException;

class OAuthException extends \Exception
{
    /**
     * @var array
     */
    private $headers = [];

    /**
     * OAuthException constructor.
     *
     * @param OAuthServerException $previous
     */
    public function __construct(OAuthServerException $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
        if ($previous->getCode() == 9) {
            $this->headers['WWW-Authenticate'] = 'Bearer';
        }
    }

    /**
     * @return OAuthServerException
     */
    private function getOAuthServerException(): OAuthServerException
    {
        $a = $this->getPrevious();
        if ($a instanceof OAuthServerException)
            return $a;
        else return null;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->getOAuthServerException()->getHttpStatusCode();
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->getOAuthServerException()->getPayload();
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}