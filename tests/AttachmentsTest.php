<?php

namespace TestMonitor\TOPdesk\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\TOPdesk\Client;

class AttachmentsTest extends TestCase
{
    /**
     * @var array
     */
    protected $incident;

    /**
     * Setup the test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->incident = [
            'id' => 1,
            'caller' => [
                'dynamicName' => 'Foo Bar',
                'email' => 'foo@bar.test',
            ],
            'status' => 'firstLine',
            'briefDescription' => 'Small issues',
            'externalNumber' => 'I123',
            'request' => 'Do something about all the problems',
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
        $response->shouldReceive('getBody')->andReturn(json_encode($this->incident));

        // When
        $result = $topdesk->addAttachment(__DIR__ . '/files/logo.png', 1);

        // Then
        $this->assertIsArray($result);
        $this->assertEquals($this->incident['caller']['dynamicName'], $result['caller']['dynamicName']);
    }
}
