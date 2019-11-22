<?php

namespace TestMonitor\TOPdesk;

use TestMonitor\TOPdesk\Actions\ManagesIncidents;
use TestMonitor\TOPdesk\Actions\ManagesAttachments;
use TestMonitor\TOPdesk\Integration\MakesHttpRequests;
use TestMonitor\TOPdesk\Transforms\TransformsIncidents;

class Client
{
    use ManagesAttachments,
        ManagesIncidents,
        MakesHttpRequests,
        TransformsIncidents;

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
     * Send a test request to TOPDesk.
     *
     * @return bool
     */
    public function test()
    {
        return (bool) $this->incidents();
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  array $collection
     * @param  string $class
     * @param  array $extraData
     *
     * @return array
     */
    protected function transformCollection($collection, $class, $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return $this->fromTopDeskIncident($data + $extraData);
        }, $collection);
    }
}
