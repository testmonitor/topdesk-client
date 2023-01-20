<?php

namespace TestMonitor\TOPdesk\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\TOPdesk\Client;
use TestMonitor\TOPdesk\Resources\Attachment;

class AttachmentsTest extends TestCase
{
    /**
     * @var array
     */
    protected $attachment;

    /**
     * Setup the test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->attachment = [
            'id' => '123',
            'fileName' => 'logo.png',
            'downloadUrl' => '/download/url',
        ];
    }

    /**
     * Teardown the test.
     */
    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_add_an_attachment_to_an_incident()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode($this->attachment)));

        // When
        $attachment = $topdesk->addAttachment(__DIR__ . '/files/logo.png', 1);

        // Then
        $this->assertInstanceOf(Attachment::class, $attachment);
        $this->assertEquals($this->attachment['id'], $attachment->id);
    }
}
