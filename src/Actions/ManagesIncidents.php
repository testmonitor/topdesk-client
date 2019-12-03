<?php

namespace TestMonitor\TOPdesk\Actions;

use TestMonitor\TOPdesk\Resources\Incident;

trait ManagesIncidents
{
    /**
     * Get all incidents.
     *
     * @return mixed
     */
    public function incidents()
    {
        $response = $this->get('tas/api/incidents');

        return array_map(function ($incident) {
            return $this->fromTopDeskIncident($incident);
        }, $response);
    }

    /**
     * @param \TestMonitor\TOPdesk\Resources\Incident $incident
     *
     * @return mixed
     */
    public function createIncident(Incident $incident)
    {
        $response =  $this->post(
            'tas/api/incidents',
            [
                'json' => $this->toTopDeskIncident($incident),
            ]
        );

        return $this->fromTopDeskIncident($response);
    }
}
