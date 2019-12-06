<?php

namespace TestMonitor\TOPdesk\Resources;

class Attachment extends Resource
{
    /**
     * The id of the attachment.
     *
     * @var string
     */
    public $id;

    /**
     * The filename of the attachment.
     *
     * @var string
     */
    public $filename;

    /**
     * The url of the attachment.
     *
     * @var string
     */
    public $url;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->filename = $attributes['filename'];
        $this->url = $attributes['url'];
    }
}
