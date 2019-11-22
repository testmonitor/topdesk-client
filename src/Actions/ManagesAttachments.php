<?php

namespace TestMonitor\TOPDesk\Actions;

trait ManagesAttachments
{

    /**
     * Add a new TOPdesk attachment.
     *
     * @param \App\Models\Media $media
     * @param string $topDeskId
     *
     * @return array
     */
    public function addAttachment(Media $media, $topDeskId)
    {
        return $this->post(
            "tas/api/incidents/id/{$topDeskId}/attachments",
            [
                'query' => ['description' => $media->file_name],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($media->getPath(), 'r'),
                    ],
                ],
            ]
        );
    }
}
