<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppTest extends TestCase
{
    use DatabaseTransactions;

    public function test_database_works()
    {
        User::factory(20)->create();
        $this->assertEquals(20, User::all()->count());
    }
}
