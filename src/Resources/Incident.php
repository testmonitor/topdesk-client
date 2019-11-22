<?php

namespace TestMonitor\TOPDesk\Resources;

class Incident extends Resource
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

    public $callerName;

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

    static public function fromArray($data)
    {
        return new self(
            $data['caller']['dynamicName'],
            $data['caller']['email'] ?? '',
            $data['status'] ?? '',
            $data['externalNumber'] ?? '',
            $data['request'] ?? '',
            $data['briefDescription'] ?? '',
            $data['id'],
        );
    }

    public function toArray()
    {
        return [
            "caller" => [
                "dynamicName" => $this->callerName,
                "email" => $this->callerEmail,
            ],
            "briefDescription" => $this->briefDescription,
            "externalNumber" => $this->number,
            "request" => $this->request,
        ];
    }
}
