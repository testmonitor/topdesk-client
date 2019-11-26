<?php

namespace TestMonitor\TOPdesk\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\TOPdesk\Client;
use TestMonitor\TOPdesk\Exceptions\NotFoundException;
use TestMonitor\TOPdesk\Exceptions\ValidationException;
use TestMonitor\TOPdesk\Exceptions\FailedActionException;
use TestMonitor\TOPdesk\Exceptions\UnauthorizedException;

class IncidentsTest extends TestCase
{
    /**
     * @var string
     */
    protected $incidents;

    /**
     * Setup the test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->incidents = json_encode([
            [
                'id' => 1,
                'caller' => [
                    'dynamicName' => 'Foo Bar',
                    'email' => 'foo@bar.test',
                ],
                'briefDescription' => 'Small issues',
                'externalNumber' => 'I123',
                'request' => 'Do something about all the problems',
            ],
        ]);
    }

    /**
     * Teardown the test.
     */
    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_incidents()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn($this->incidents);

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        // When
        $incidents = $topdesk->incidents();

        // Then
        $this->assertIsArray($incidents);
        $this->assertEquals('Do something about all the problems', array_pop($incidents)->request);
    }

    /** @test */
    public function it_should_test_the_connection()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn($this->incidents);

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        // When
        $result = $topdesk->test();

        // Then
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_create_an_incident()
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
        $result = $topdesk->createIncident(new \TestMonitor\TOPDesk\Resources\Incident(
            'John Doe',
            'johndoe@testmonitor.com',
            'firstLine',
            'I1234',
            'Some Request',
            'Some Request Description'
        ));

        // Then
        $this->assertIsArray($result);
        $this->assertEquals('John Doe', $result['caller']['dynamicName']);
    }

    /** @test */
    public function it_should_throw_an_exception_when_user_is_unauthorized()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(401);

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        $this->expectException(UnauthorizedException::class);

        // When
        $topdesk->test();
    }

    /** @test */
    public function it_should_throw_an_exception_when_the_resource_is_not_found()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(404);

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        $this->expectException(NotFoundException::class);

        // When
        $topdesk->test();
    }

    /** @test */
    public function it_should_throw_an_exception_when_the_request_is_invalid()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['foo' => 'bar']));

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        $this->expectException(ValidationException::class);

        // When
        $topdesk->test();
    }

    /** @test */
    public function it_should_throw_an_exception_when_the_action_is_failed()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturn('Message');

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        $this->expectException(FailedActionException::class);

        // When
        $topdesk->test();
    }
}
