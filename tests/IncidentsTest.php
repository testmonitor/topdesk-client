<?php

namespace TestMonitor\TOPdesk\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\TOPdesk\Client;
use TestMonitor\TOPdesk\Resources\Incident;
use TestMonitor\TOPdesk\Exceptions\NotFoundException;
use TestMonitor\TOPdesk\Exceptions\ValidationException;
use TestMonitor\TOPdesk\Exceptions\FailedActionException;
use TestMonitor\TOPdesk\Exceptions\UnauthorizedException;

class IncidentsTest extends TestCase
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
    public function it_should_return_a_list_of_incidents()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode([$this->incident]));

        // When
        $incidents = $topdesk->incidents();

        // Then
        $this->assertIsArray($incidents);
        $this->assertCount(1, $incidents);
        $this->assertInstanceOf(Incident::class, $incidents[0]);
        $this->assertEquals($this->incident['request'], $incidents[0]->request);
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_incidents()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(FailedActionException::class);

        // When
        $topdesk->incidents();
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_incidents()
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
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_incidents()
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
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_incidents()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['message' => 'invalid']));

        $this->expectException(ValidationException::class);

        // When
        $topdesk->incidents();
    }

    /** @test */
    public function it_should_return_an_error_message_when_client_provides_invalid_data_while_a_getting_list_of_projects()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['errors' => ['invalid']]));

        // When
        try {
            $topdesk->incidents();
        } catch (ValidationException $exception) {
            // Then
            $this->assertIsArray($exception->errors());
            $this->assertEquals('invalid', $exception->errors()['errors'][0]);
        }
    }

    /** @test */
    public function it_should_throw_a_generic_exception_when_client_suddenly_becomes_a_teapot_while_a_getting_list_of_incidents()
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

    /** @test */
    public function it_should_create_an_incident()
    {
        // Given
        $topdesk = new Client('url', 'user', 'pass');

        $topdesk->setClient($service = Mockery::mock('GuzzleHttp\Client'));

        $service->shouldReceive('request')->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode($this->incident));

        // When
        $incident = $topdesk->createIncident(new \TestMonitor\TOPdesk\Resources\Incident([
            'callerName' => $this->incident['caller']['dynamicName'],
            'callerEmail' => $this->incident['caller']['email'],
            'status' => $this->incident['status'],
            'number' => $this->incident['externalNumber'],
            'briefDescription' => $this->incident['briefDescription'],
            'request' => $this->incident['request'],
        ]));

        // Then
        $this->assertInstanceOf(Incident::class, $incident);
        $this->assertEquals($this->incident['caller']['dynamicName'], $incident->callerName);
        $this->assertIsArray($incident->toArray());
    }
}
