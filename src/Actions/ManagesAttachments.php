<?php

namespace TestMonitor\TOPdesk\Actions;

trait ManagesAttachments
{
    /**
     * Add a new TOPdesk attachment.
     *
     * @param string $path
     * @param $topDeskId
     * @param string|null $filename
     *
     * @return mixed
     */
    public function addAttachment(string $path, $topDeskId, ?string $filename = null)
    {
        return $this->post(
            "tas/api/incidents/id/{$topDeskId}/attachments",
            [
                'query' => ['description' =>  $filename ?? basename($path)],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($path, 'r'),
                    ],
                ],
            ]
        );
    }
}
