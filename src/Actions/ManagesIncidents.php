<?php

namespace TestMonitor\TOPdesk\Actions;

use TestMonitor\TOPdesk\Validator;
use TestMonitor\TOPdesk\Resources\Incident;
use TestMonitor\TOPdesk\Transforms\TransformsIncidents;

trait ManagesIncidents
{
    use TransformsIncidents;

    /**
     * Get all incidents.
     *
     * @return array
     */
    public function incidents()
    {
        $response = $this->get('tas/api/incidents');

        Validator::isArray($response);

        return array_map(function ($incident) {
            return $this->fromTopDeskIncident($incident);
        }, $response);
    }

    /**
     * @param \TestMonitor\TOPdesk\Resources\Incident $incident
     * @return Incident
     */
    public function createIncident(Incident $incident): Incident
    {
        $response = $this->post(
            'tas/api/incidents',
            [
                'json' => $this->toTopDeskIncident($incident),
            ]
        );

        return $this->fromTopDeskIncident($response);
    }
}
