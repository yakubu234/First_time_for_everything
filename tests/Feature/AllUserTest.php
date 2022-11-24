<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use \Spiritix\LadaCache\Database\LadaCacheTrait;
use Tests\TestCase;

class AllUserTest extends TestCase
{
    use DatabaseMigrations;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    public function setUp(): Void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllUsers()
    {
        Redis::del('users');
        $response = $this->get('/api/all-users');

        $response->dump()->assertStatus(200);
    }
}
