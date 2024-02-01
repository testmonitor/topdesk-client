<?php

namespace TestMonitor\TOPdesk\Tests;

use PHPUnit\Framework\TestCase;
use TestMonitor\TOPdesk\Builders\FIQL\Field;
use TestMonitor\TOPdesk\Builders\FIQL\FIQL;
use TestMonitor\TOPdesk\Builders\FIQL\Operator;

class FIQLTest extends TestCase
{
    /** @test */
    public function it_should_return_a_fiql_builder()
    {
        // Given

        // When
        $builder = (new FIQL);

        // Then
        $this->assertInstanceOf(FIQL::class, $builder);
    }

    /** @test */
    public function it_should_generate_a_wiql_query()
    {
        // Given

        // When
        $query = (new FIQL)->getQuery();

        // Then
        $this->assertIsString($query);
        $this->assertEquals('', $query);
    }

    /** @test */
    public function it_should_generate_a_fiql_query_with_a_single_condition()
    {
        // Given

        // When
        $query = (new FIQL)->where(Field::ID, Operator::EQUALS, '123')->getQuery();

        // Then
        $this->assertIsString($query);
        $this->assertEquals('id==\'123\'', $query);
    }

    /** @test */
    public function it_should_generate_a_fiql_query_with_an_or_condition()
    {
        // Given

        // When
        $query = (new FIQL)
            ->where(Field::BRIEF_DESCRIPTION, Operator::STARTS_WITH, 'test')
            ->orWhere(Field::REQUEST, Operator::STARTS_WITH, 'monitor')
            ->getQuery();

        // Then
        $this->assertIsString($query);
        $this->assertEquals('briefDescription=sw=\'test\',request=sw=\'monitor\'', $query);
    }

    /** @test */
    public function it_should_generate_a_fiql_query_with_multiple_conditions()
    {
        // Given

        // When
        $query = (new FIQL)
            ->where(Field::EXTERNAL_NUMBER, Operator::EQUALS, 'I1')
            ->where(Field::NUMBER, Operator::NOT_EQUALS, 'I2')
            ->getQuery();

        // Then
        $this->assertIsString($query);
        $this->assertEquals('externalNumber==\'I1\';number!=\'I2\'', $query);
    }

    /** @test */
    public function it_should_generate_a_fiql_query_with_a_condition_that_uses_multiple_values()
    {
        // Given

        // When
        $query = (new FIQL)->where(Field::STATUS, Operator::IN, ['firstline', 'secondline'])->getQuery();

        // Then
        $this->assertIsString($query);
        $this->assertEquals('status=in=(\'firstline\',\'secondline\')', $query);
    }

    /** @test */
    public function it_should_generate_a_fiql_query_using_a_conditionable_query_that_evaluates_as_true()
    {
        // Given

        // When
        $query = (new FIQL)->when(true, function (FIQL $query) {
            return $query->where(Field::CALLER_NAME, Operator::EQUALS, 'Bert');
        })->getQuery();

        // Then
        $this->assertIsString($query);
        $this->assertEquals('caller.dynamicName==\'Bert\'', $query);
    }

    /** @test */
    public function it_should_generate_a_fiql_query_using_a_conditionable_query_that_evaluates_as_false()
    {
        // Given

        // When
        $query = (new FIQL)->when(false, function (FIQL $query) {
            return $query->where(Field::ACTION, Operator::EQUALS, 'none');
        })->getQuery();

        // Then
        $this->assertIsString($query);
        $this->assertEquals('', $query);
    }
}
