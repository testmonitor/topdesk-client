<?php

namespace TestMonitor\TOPdesk\Integration;

use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use TestMonitor\TOPdesk\Exceptions\NotFoundException;
use TestMonitor\TOPdesk\Exceptions\ValidationException;
use TestMonitor\TOPdesk\Exceptions\FailedActionException;
use TestMonitor\TOPdesk\Exceptions\UnauthorizedException;

trait MakesHttpRequests
{
    /**
     * @var
     */
    public $guzzle;

    /**
     * Make a GET request to TopDesk servers and return the response.
     *
     * @param  string $uri
     *
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     * @return mixed
     */
    private function get($uri)
    {
        return $this->request('GET', $uri);
    }

    /**
     * Make a POST request to TopDesk servers and return the response.
     *
     * @param  string $uri
     * @param  array $payload
     *
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     * @return mixed
     */
    private function post($uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make a PUT request to TopDesk servers and return the response.
     *
     * @param  string $uri
     * @param  array $payload
     *
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     * @return mixed
     */
    private function patch($uri, array $payload = [])
    {
        return $this->request('PATCH', $uri, $payload);
    }

    /**
     * Make a DELETE request to TopDesk servers and return the response.
     *
     * @param  string $uri
     * @param  array $payload
     *
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     * @return mixed
     */
    private function delete($uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make request to TopDesk servers and return the response.
     *
     * @param  string $verb
     * @param  string $uri
     * @param  array $payload
     *
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     * @return mixed
     */
    private function request($verb, $uri, array $payload = [])
    {
        $response = $this->guzzle()->request(
            $verb,
            $uri,
            $payload
        );

        if (! in_array($response->getStatusCode(), [200, 201, 204, 206])) {
            $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \Exception
     * @return void
     */
    private function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 401) {
            throw new UnauthorizedException();
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        throw new Exception((string) $response->getStatusCode());
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function guzzle()
    {
        return $this->guzzle ?: new Client([
            'base_uri' => $this->instance . '/',
            'auth' => [$this->username, $this->password],
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }
}
