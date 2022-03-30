<?php

namespace TestMonitor\TOPdesk\Resources;

class Branch extends Resource
{
    /**
     * The id of the branche.
     *
     * @var string
     */
    public $id;

    /**
     * The name of the branche.
     *
     * @var string
     */
    public $name;

    /**
     * Incident constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? null;
    }
}
