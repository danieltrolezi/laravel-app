<?php

namespace Tests\Unit\Enums;

use Tests\TestCase;

abstract class EnumTestCase extends TestCase
{
    protected string $enumerator;

    protected function getCases(): array
    {
        return call_user_func([$this->enumerator, 'cases']);
    }

    protected function getNames(): array
    {
        return array_column($this->getCases(), 'name');
    }

    protected function getValues(): array
    {
        return array_column($this->getCases(), 'value');
    }

    public function test_names()
    {
        $this->assertEquals(
            $this->getNames(),
            call_user_func([$this->enumerator, 'names'])
        );
    }

    public function test_values()
    {
        $this->assertEquals(
            $this->getValues(),
            call_user_func([$this->enumerator, 'values'])
        );
    }

    public function test_array()
    {
        $this->assertEquals(
            array_combine($this->getNames(), $this->getValues()),
            call_user_func([$this->enumerator, 'array'])
        );
    }

    public function test_names_as_string()
    {
        $this->assertEquals(
            implode(',', $this->getNames()),
            call_user_func([$this->enumerator, 'namesAsString'])
        );
    }

    public function test_values_as_string()
    {
        $this->assertEquals(
            implode(',', $this->getValues()),
            call_user_func([$this->enumerator, 'valuesAsString'])
        );
    }
}
