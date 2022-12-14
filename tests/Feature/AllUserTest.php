<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use \Spiritix\LadaCache\Database\LadaCacheTrait;
use Tests\TestCase;

class AllUserTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;



    protected static $migrated = false;

    public function setUp(): Void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        DB::beginTransaction();
        $this->seed();
    }

    public function tearDown(): Void
    {
        DB::rollback();
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllUsers()
    {
        Redis::del('*users*');
        $response = $this->get('/api/all-users');

        $response->dump()->assertStatus(200);
    }
}
