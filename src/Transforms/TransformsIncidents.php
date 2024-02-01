<?php

namespace TestMonitor\TOPdesk\Transforms;

use TestMonitor\TOPdesk\Validator;
use TestMonitor\TOPdesk\Resources\Incident;

trait TransformsIncidents
{
    /**
     * @param \TestMonitor\TOPdesk\Resources\Incident $incident
     * @return array
     */
    protected function toTopDeskIncident(Incident $incident): array
    {
        return array_filter([
            'caller' => array_filter([
                'branch' => array_filter([
                    'id' => $incident->branch,
                ]),
                'dynamicName' => $incident->callerName,
                'email' => $incident->callerEmail,
            ]),
            'briefDescription' => $incident->briefDescription,
            'externalNumber' => $incident->number,
            'request' => $incident->request,
        ]);
    }

    /**
     * @param array $incidents
     *
     * @throws \TestMonitor\TOPdesk\Exceptions\InvalidDataException
     *
     * @return \TestMonitor\TOPdesk\Resources\Incident[]
     */
    protected function fromTopDeskIncidents(array $incidents): array
    {
        Validator::isArray($incidents);

        return array_map(function ($incident) {
            return $this->fromTopDeskIncident($incident);
        }, $incidents);
    }

    /**
     * @param array $incident
     * @return \TestMonitor\TOPdesk\Resources\Incident
     */
    protected function fromTopDeskIncident(array $incident): Incident
    {
        Validator::keyExists($incident, 'id');

        return new Incident([
            'id' => $incident['id'],
            'branch' => $incident['branch']['id'] ?? '',
            'briefDescription' => $incident['briefDescription'] ?? '',
            'callerName' => $incident['caller']['dynamicName'] ?? '',
            'callerEmail' => $incident['caller']['email'] ?? '',
            'externalNumber' => $incident['externalNumber'] ?? '',
            'number' => $incident['number'] ?? '',
            'request' => $incident['request'] ?? '',
            'status' => $incident['status'] ?? '',
        ]);
    }
}
