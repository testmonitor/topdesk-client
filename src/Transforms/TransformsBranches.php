<?php

namespace TestMonitor\TOPdesk\Transforms;

use TestMonitor\TOPdesk\Validator;
use TestMonitor\TOPdesk\Resources\Branch;

trait TransformsBranches
{
    /**
     * @param array $branch
     * @return \TestMonitor\TOPdesk\Resources\Branch
     */
    protected function fromTopDeskBranch(array $branch): Branch
    {
        Validator::keyExists($branch, 'id');

        return new Branch([
            'id' => $branch['id'],
            'name' => $branch['name'],
        ]);
    }
}
