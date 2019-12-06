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
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn($this->incidents);

        // When
        $incidents = $topdesk->incidents();

        // Then
        $this->assertIsArray($incidents);
        $this->assertEquals('Do something about all the problems', array_pop($incidents)->request);
    }

    /** @test */
    public function it_should_create_an_incident()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
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

        // When
        $incident = $topdesk->createIncident(new \TestMonitor\TOPDesk\Resources\Incident([
            'callerName' => 'John Doe',
            'callerEmail' => 'johndoe@testmonitor.com',
            'status' => 'firstLine',
            'number' => 'I1234',
            'briefDescription' => 'Some Request',
            'request' => 'Some Request Description',
        ]));

        // Then
        $this->assertInstanceOf(\TestMonitor\TOPDesk\Resources\Incident::class, $incident);
        $this->assertEquals('John Doe', $incident->callerName);
        $this->assertIsArray($incident->toArray());
    }

    /** @test */
    public function it_should_throw_an_exception_when_user_is_unauthorized()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);

        $this->expectException(UnauthorizedException::class);

        // When
        $topdesk->incidents();
    }

    /** @test */
    public function it_should_throw_an_exception_when_user_has_not_the_correct_permissions()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(403);

        $this->expectException(UnauthorizedException::class);

        // When
        $topdesk->incidents();
    }

    /** @test */
    public function it_should_throw_an_exception_when_the_resource_is_not_found()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);

        $this->expectException(NotFoundException::class);

        // When
        $topdesk->incidents();
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
        $topdesk->incidents();
    }

    /** @test */
    public function it_should_return_a_list_of_errors_wheb_validation_is_failed()
    {
        // Given
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['message' => 'invalid']));

        $guzzle = Mockery::mock('GuzzleHttp\Client');
        $guzzle->shouldReceive('request')->andReturn($response);

        $topdesk = new Client('url', 'user', 'pass');
        $topdesk->setClient($guzzle);

        // When
        $topdesk->incidents();
    }

    /** @test */
    public function it_should_throw_an_exception_when_the_response_code_is_other_then_ok()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(418);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(\Exception::class);

        // When
        $topdesk->incidents();
    }
}
