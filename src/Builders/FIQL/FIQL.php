<?php

namespace TestMonitor\TOPdesk\Builders\FIQL;

use Closure;

class FIQL
{
    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * Add a new where condition.
     *
     * @param string $field
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return \TestMonitor\TOPdesk\Builders\FIQL\FIQL
     */
    public function where(
        string $field,
        mixed $operator = Operator::EQUALS,
        mixed $value = null,
        string $boolean = Keyword::AND
    ): self {
        $this->conditions[] = compact('field', 'operator', 'value', 'boolean');

        return $this;
    }

    /**
     * Add a new where condition (using OR).
     *
     * @param string $field
     * @param mixed $operator
     * @param mixed $value
     * @return \TestMonitor\TOPdesk\Builders\FIQL\FIQL
     */
    public function orWhere(string $field, mixed $operator = Operator::EQUALS, mixed $value = null): self
    {
        return $this->where($field, $operator, $value, Keyword::OR);
    }

    /**
     * Executes the callback when value is true.
     *
     * @param mixed $value
     * @param callable $callback
     * @return \TestMonitor\TOPdesk\Builders\FIQL\FIQL
     */
    public function when(mixed $value, callable $callback): self
    {
        $value = $value instanceof Closure ? $value($this) : $value;

        if ($value) {
            return $callback($this, $value) ?? $this;
        }

        return $this;
    }

    /**
     * Quotes a value based on its operator.
     *
     * @param string $operator
     * @param mixed $value
     * @return string
     */
    public function quote(string $operator, mixed $value): string
    {
        if (in_array($operator, [Operator::IN, Operator::NOT_IN], true)) {
            $values = implode(
                ',',
                array_map(
                    fn ($value) => "'$value'",
                    (array) $value
                )
            );

            return "($values)";
        }

        return "'{$value}'";
    }

    /**
     * Generates conditions based on the available "wheres".
     *
     * @return string
     */
    public function getConditions(): string
    {
        $conditions = '';

        foreach ($this->conditions as $key => $condition) {
            $conditions .= $key !== array_key_first($this->conditions) ? "{$condition['boolean']}" : '';

            $values = $this->quote($condition['operator'], $condition['value']);

            $conditions .= "{$condition['field']}{$condition['operator']}{$values}";
        }

        return $conditions;
    }

    /**
     * Generates the FIQL query.
     *
     * @return string
     */
    public function getQuery(): string
    {
        return trim($this->getConditions());
    }
}
