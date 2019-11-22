<?php

namespace TestMonitor\TOPdesk\Actions;

trait ManagesAttachments
{
    /**
     * Add a new TOPDesk attachment.
     *
     * @param string $path
     * @param $topDeskId
     * @param string|null $filename
     *
     * @return mixed
     */
    public function addAttachment(string $path, $topDeskId, ?string $filename = null)
    {
        if (! $filename) {
            $explodedPath = explode(DIRECTORY_SEPARATOR, $path);
            $filename = array_pop($explodedPath);
        }

        return $this->post(
            "tas/api/incidents/id/{$topDeskId}/attachments",
            [
                'query' => ['description' => $filename],
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
