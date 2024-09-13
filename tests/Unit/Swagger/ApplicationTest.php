<?php

namespace Tests\Unit\Swagger;

use App\Swagger\Application;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public function test_should_create_instance()
    {
        $app = new Application();
        $this->assertInstanceOf(Application::class, $app);
        $this->assertNull($app->tags());
        $this->assertNull($app->up());
        $this->assertNull($app->apiUp());
    }
}
