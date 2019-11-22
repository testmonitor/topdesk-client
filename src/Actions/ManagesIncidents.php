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
        return $this->transformCollection(
            $this->get('tas/api/incidents'),
            Incident::class
        );
    }

    /**
     * @param \TestMonitor\TOPdesk\Resources\Incident $incident
     *
     * @return mixed
     */
    public function createIncident(Incident $incident)
    {
        return $this->post(
            'tas/api/incidents',
            [
                'json' => $this->toTopDeskIncident($incident),
            ]
        );
    }
}
