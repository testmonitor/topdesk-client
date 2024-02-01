<?php

namespace TestMonitor\TOPdesk\Resources;

class Incident extends Resource
{
    /**
     * The id of the incident.
     *
     * @var string
     */
    public $id;

    /**
     * The branch of the incident.
     *
     * @var string
     */
    public $branch;

    /**
     * The status of the incident.
     *
     * @var string
     */
    public $status;

    /**
     * The number of the incident.
     *
     * @var string
     */
    public $number;

    /**
     * The external number of the incident.
     *
     * @var string
     */
    public $externalNumber;

    /**
     * The request of the incident.
     *
     * @var string
     */
    public $request;

    /**
     * The description of the incident.
     *
     * @var string
     */
    public $briefDescription;

    /**
     * @var string
     */
    public $callerName;

    /**
     * @var string
     */
    public $callerEmail;

    /**
     * Incident constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->branch = $attributes['branch'] ?? null;
        $this->status = $attributes['status'] ?? 'firstLine';
        $this->number = $attributes['number'];
        $this->externalNumber = $attributes['externalNumber'] ?? '';
        $this->request = $attributes['request'];
        $this->briefDescription = $attributes['briefDescription'];
        $this->callerEmail = $attributes['callerEmail'];
        $this->callerName = $attributes['callerName'];
    }
}
