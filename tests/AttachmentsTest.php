<?php

namespace TestMonitor\TOPdesk\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\TOPdesk\Client;

class AttachmentsTest extends TestCase
{
    /** @test */
    public function it_should_add_an_attachment_to_an_incident()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode([
            'id' => 1,
            'caller' => [
                'dynamicName' => 'John Doe',
                'email' => 'johndoe@testmonitor.com',
            ],
            'briefDescription' => 'Some Request Description',
            'externalNumber' => 'I1234',
            'request' => 'Some Request',
        ]));

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        // When
        $result = $topdesk->addAttachment(__DIR__ . '/logo.png', 1);

        // Then
        $this->assertIsArray($result);
        $this->assertEquals('John Doe', $result['caller']['dynamicName']);
    }

    /**
     * Teardown the test.
     */
    public function tearDown(): void
    {
        Mockery::close();
    }
}
