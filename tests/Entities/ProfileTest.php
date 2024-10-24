<?php

namespace WalkerChiu\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use WalkerChiu\Account\Models\Entities\Profile;

class ProfileTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\Account\AccountServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * A basic functional test on Profile.
     *
     * For WalkerChiu\Account\Models\Entities\Profile
     * 
     * @return void
     */
    public function testProfile()
    {
        $faker = \Faker\Factory::create();

        DB::table(config('wk-core.table.user'))->insert([
            'name'     => $faker->username,
            'email'    => $faker->email,
            'password' => $faker->password
        ]);

        // Give
        $record_1 = factory(Profile::class)->create();
        $record_2 = factory(Profile::class)->create();

        // Get records after creation
            // When
            $records = Profile::all();
            // Then
            $this->assertCount(2, $records);

        // Delete someone
            // When
            $record_2->delete();
            $records = Profile::all();
            // Then
            $this->assertCount(1, $records);
    }
}
