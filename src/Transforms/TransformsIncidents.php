<?php

namespace TestMonitor\TOPdesk\Transforms;

use TestMonitor\TOPdesk\Resources\Incident;

trait TransformsIncidents
{
    /**
     * @param \TestMonitor\TOPdesk\Resources\Incident $incident
     *
     * @return array
     */
    protected function toTopDeskIncident(Incident $incident): array
    {
        return [
            'caller' => [
                'dynamicName' => $incident->callerName,
                'email' => $incident->callerEmail,
            ],
            'briefDescription' => $incident->briefDescription,
            'externalNumber' => $incident->number,
            'request' => $incident->request,
        ];
    }

    /**
     * @param array $incident
     *
     * @return \TestMonitor\TOPdesk\Resources\Incident
     */
    protected function fromTopDeskIncident(array $incident): Incident
    {
        return new Incident(
            $incident['caller']['dynamicName'],
            $incident['caller']['email'] ?? '',
            $incident['status'] ?? '',
            $incident['externalNumber'] ?? '',
            $incident['request'] ?? '',
            $incident['briefDescription'] ?? '',
            $incident['id']
        );
    }
}
