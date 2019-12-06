<?php

namespace TestMonitor\TOPdesk\Actions;

trait ManagesAttachments
{
    /**
     * Add a new TOPdesk attachment.
     *
     * @param string $path
     * @param $topDeskId
     *
     * @return mixed
     */
    public function addAttachment(string $path, $topDeskId)
    {
        return $this->post(
            "tas/api/incidents/id/{$topDeskId}/attachments",
            [
                'query' => ['description' =>  basename($path)],
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
