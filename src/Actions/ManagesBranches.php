<?php

namespace TestMonitor\TOPdesk\Actions;

use TestMonitor\TOPdesk\Validator;
use TestMonitor\TOPdesk\Transforms\TransformsBranches;

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
