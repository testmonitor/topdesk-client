<?php

namespace TestMonitor\TOPdesk\Actions;

use TestMonitor\TOPdesk\Resources\Attachment;
use TestMonitor\TOPdesk\Transforms\TransformsAttachments;

trait ManagesAttachments
{
    use TransformsAttachments;

    /**
     * Add a new TOPdesk attachment.
     *
     * @param string $path
     * @param string $topDeskId
     * @return Attachment
     */
    public function addAttachment(string $path, $topDeskId): Attachment
    {
        $response = $this->post(
            "tas/api/incidents/id/{$topDeskId}/attachments",
            [
                'query' => ['description' => basename($path)],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($path, 'r'),
                    ],
                ],
            ]
        );

        return $this->fromTopDeskAttachment($response);
    }
}
