<?php

namespace TestMonitor\TOPdesk\Actions;

use TestMonitor\TOPdesk\Validator;
use TestMonitor\TOPdesk\Resources\Incident;
use TestMonitor\TOPdesk\Transforms\TransformsBranches;
use TestMonitor\TOPdesk\Transforms\TransformsIncidents;

trait ManagesBranches
{
    use TransformsBranches;

    /**
     * Get all branches.
     *
     * @return array
     */
    public function branches()
    {
        $response = $this->get('/tas/api/branches');

        Validator::isArray($response);

        return array_map(function ($branche) {
            return $this->fromTopDeskBranche($branche);
        }, $response);
    }
}
