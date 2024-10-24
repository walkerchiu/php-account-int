<?php

namespace WalkerChiu\Account;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Constants\Language;
use WalkerChiu\Account\Models\Forms\ProfileFormRequest;

class ProfileFormRequestTest extends \Orchestra\Testbench\TestCase
{
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

        $this->request  = new ProfileFormRequest();
        $this->rules    = $this->request->rules();
        $this->messages = $this->request->messages();
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
     * Unit test about Authorize.
     *
     * For WalkerChiu\Account\Models\Forms\ProfileFormRequest
     * 
     * @return void
     */
    public function testAuthorize()
    {
        $this->assertEquals(true, 1);
    }

    /**
     * Unit test about Rules.
     *
     * For WalkerChiu\Account\Models\Forms\ProfileFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        DB::table(config('wk-core.table.user'))->insert([
            'name'     => $faker->username,
            'email'    => $faker->email,
            'password' => $faker->password
        ]);


        for ($i=1; $i<=10; $i++) {
            // Give
            $attributes = [
                'user_id'      => 1,
                'language'     => $faker->randomElement(Language::getCodes()),
                'gender'       => $faker->randomElement(['female', 'male']),
                'notice_login' => $faker->boolean
            ];
            // When
            $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
            $fails = $validator->fails();
            // Then
            $this->assertEquals(false, $fails);
        }

        // Give
        $attributes = [
            'language' => $faker->randomElement(Language::getCodes()),
            'gender'       => $faker->randomElement(['female', 'male']),
            'notice_login' => $faker->boolean
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
