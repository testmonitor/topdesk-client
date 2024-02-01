<?php

namespace TestMonitor\TOPdesk\Actions;

use TestMonitor\TOPdesk\Transforms\TransformsBranches;

trait ManagesBranches
{
    use TransformsBranches;

    /**
     * Get all branches.
     *
     * @return \TestMonitor\TOPdesk\Resources\Branch[]
     */
    public function branches()
    {
        $response = $this->get('/tas/api/branches');

        return $this->fromTopDeskBranches($response);
    }
}
