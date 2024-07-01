<?php

namespace TestMonitor\TOPdesk;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use TestMonitor\TOPdesk\Actions\ManagesBranches;
use TestMonitor\TOPdesk\Actions\ManagesIncidents;
use TestMonitor\TOPdesk\Actions\ManagesAttachments;
use TestMonitor\TOPdesk\Exceptions\NotFoundException;
use TestMonitor\TOPdesk\Exceptions\ValidationException;
use TestMonitor\TOPdesk\Exceptions\FailedActionException;
use TestMonitor\TOPdesk\Exceptions\UnauthorizedException;

class Client
{
    use ManagesAttachments,
        ManagesIncidents,
        ManagesBranches;

    /**
     * @var string
     */
    protected $instance;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $partnerId;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new client instance.
     *
     * @param string $instance
     * @param string $username
     * @param string $password
     * @param string $partnerId
     */
    public function __construct($instance, $username, $password, $partnerId = '')
    {
        $this->instance = $instance;
        $this->username = $username;
        $this->password = $password;
        $this->partnerId = $partnerId;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function client()
    {
        return $this->client ?? new \GuzzleHttp\Client([
            'base_uri' => $this->instance,
            'auth' => [$this->username, $this->password],
            'http_errors' => false,
            'allow_redirects' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Partner-Solution-ID' => $this->partnerId,
            ],
        ]);
    }

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * Make a GET request to TopDesk servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function get($uri, array $payload = [])
    {
        return $this->request('GET', $uri, $payload);
    }

    /**
     * Make a POST request to TopDesk servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function post($uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make request to TopDesk servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \TestMonitor\TOPdesk\Exceptions\NotFoundException
     * @throws \TestMonitor\TOPdesk\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function request($verb, $uri, array $payload = [])
    {
        $response = $this->client()->request(
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
     * @throws \TestMonitor\TOPdesk\Exceptions\UnauthorizedException
     * @throws \TestMonitor\TOPdesk\Exceptions\FailedActionException
     * @throws \Exception
     */
    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 404 || $response->getStatusCode() == 302) {
            throw new NotFoundException((string) $response->getBody(), $response->getStatusCode());
        }

        if ($response->getStatusCode() == 401 || $response->getStatusCode() == 403) {
            throw new UnauthorizedException((string) $response->getBody(), $response->getStatusCode());
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string) $response->getBody(), $response->getStatusCode());
        }

        throw new Exception((string) $response->getStatusCode());
    }
}
