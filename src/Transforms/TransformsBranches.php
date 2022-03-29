<?php

namespace TestMonitor\TOPdesk\Transforms;

use TestMonitor\TOPdesk\Resources\Branche;
use TestMonitor\TOPdesk\Validator;

trait TransformsBranches
{
    /**
     * @param array $incident
     * @return \TestMonitor\TOPdesk\Resources\Branche
     */
    protected function fromTopDeskBranche(array $branche): branche
    {
        Validator::keyExists($branche, 'id');

        return new Branche([
            'id' => $branche['id'],
            'name' => $branche['name'],
        ]);
    }
}
