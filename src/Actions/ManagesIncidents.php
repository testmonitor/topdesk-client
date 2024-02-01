<?php

namespace TestMonitor\TOPdesk\Actions;

use TestMonitor\TOPdesk\Builders\FIQL\FIQL;
use TestMonitor\TOPdesk\Resources\Incident;
use TestMonitor\TOPdesk\Transforms\TransformsIncidents;

trait ManagesIncidents
{
    use TransformsIncidents;

    /**
     * Get a single incident.
     *
     * @param string $id
     * @return \TestMonitor\TOPdesk\Resources\Incident
     */
    public function incident($id)
    {
        $response = $this->get("tas/api/incidents/id/{$id}");

        return $this->fromTopDeskIncident($response);
    }

    /**
     * Get a list of incidents.
     *
     * @param \TestMonitor\TOPdesk\Builders\FIQL\FIQL|null $query
     * @param int $start
     * @param int $limit
     * @return \TestMonitor\TOPdesk\Resources\Incident[]
     */
    public function incidents(?FIQL $query = null, int $start = 0, int $limit = 10)
    {
        $response = $this->get('tas/api/incidents', [
            'query' => [
                'query' => $query instanceof FIQL ? $query->getQuery() : (new FIQL)->getQuery(),
                'pageSize' => $limit,
                'pageStart' => $start,
            ],
        ]);

        if (empty($response)) {
            return [];
        }

        return $this->fromTopDeskIncidents($response);
    }

    /**
     * @param \TestMonitor\TOPdesk\Resources\Incident $incident
     * @return \TestMonitor\TOPdesk\Resources\Incident
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
