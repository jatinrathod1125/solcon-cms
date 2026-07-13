<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Vite;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        Vite::spy();
    }

    /**
     * Seed the database with test fixtures during automated tests.
     *
     * @param  array|string|null  $class
     * @return $this
     */
    public function seed($class = null)
    {
        if ($class === null) {
            $class = \Database\Seeders\TestFixtureSeeder::class;
        }

        return parent::seed($class);
    }
}
