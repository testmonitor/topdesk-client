<?php

namespace TestMonitor\TOPdesk\Transforms;

use TestMonitor\TOPdesk\Validator;
use TestMonitor\TOPdesk\Resources\Attachment;

trait TransformsAttachments
{
    /**
     * @param array $attachment
     * @return \TestMonitor\TOPdesk\Resources\Attachment
     */
    protected function fromTopDeskAttachment(array $attachment): Attachment
    {
        Validator::keysExists($attachment, ['id', 'fileName', 'downloadUrl']);

        return new Attachment([
            'id' => $attachment['id'],
            'filename' => $attachment['fileName'],
            'url' => $attachment['downloadUrl'],
        ]);
    }
}
