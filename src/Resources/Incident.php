<?php

namespace TestMonitor\TOPdesk\Resources;

class Incident
{
    /**
     * The id of the incident.
     *
     * @var string
     */
    public $id;

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
     * @param string $status
     * @param string $number
     * @param string $request
     * @param string $briefDescription
     * @param string|null $id
     */
    public function __construct(
        string $callerName,
        string $callerEmail,
        string $status,
        string $number,
        string $request,
        string $briefDescription,
        ?string $id = null
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->number = $number;
        $this->request = $request;
        $this->briefDescription = $briefDescription;
        $this->callerEmail = $callerEmail;
        $this->callerName = $callerName;
    }
}
