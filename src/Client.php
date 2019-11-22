<?php

namespace TestMonitor\TOPDesk;

use TestMonitor\TOPDesk\Actions\ManagesAttachments;
use TestMonitor\TOPDesk\Actions\ManagesIncidents;
use TestMonitor\TopDeskIntegration\MakesHttpRequests;

class Client
{
    use ManagesAttachments,
        ManagesIncidents,
        MakesHttpRequests;

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
     * Create a new client instance.
     *
     * @param string $instance
     * @param string $username
     * @param string $password
     */
    public function __construct($instance, $username, $password)
    {
        $this->instance = $instance;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Verify the TOPdesk URL and credentials.
     *
     * @param string $url
     * @param string $username
     * @param string $password
     *
     * @throws \App\Integrations\TopDesk\Exceptions\NotFoundException
     * @throws \App\Integrations\TopDesk\Exceptions\UnauthorizedException
     *
     * @return bool
     */
    public static function check($url, $username, $password)
    {
        $topDesk = new self($url, $username, $password);

        // Attempt a simple request.
        return (bool) $topDesk->incidents();
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  array $collection
     * @param  string $class
     * @param  array $extraData
     * @return array
     */
    protected function transformCollection($collection, $class, $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return $class::fromArray($data + $extraData);
        }, $collection);
    }
}