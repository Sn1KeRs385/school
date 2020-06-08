<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
//    use RefreshDatabase;
    use WithFaker;

    protected static $migrated = false;
    protected static $last = 0;

    protected function setUp(): void
    {
        parent::setUp();
        if (!self::$migrated) {
            if(is_dir('./storage/framework/indexes')) {
                exec('rm -R ./storage/framework/indexes');
                mkdir('./storage/framework/indexes');
            }
            Artisan::call('migrate:fresh');
            Artisan::call('passport:install');
            Artisan::call('db:seed');

            self::$migrated = true;
            self::$last = round(microtime(true) * 1000);
        }
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollback();
        parent::tearDown();
        print(get_class($this).': ' . (round(microtime(true) * 1000) - self::$last) . " ms \n");
        self::$last = round(microtime(true) * 1000);
    }

    protected function assertArraySubsetNew(array $subset, array $haystack) {
        foreach ($subset as $key => $value) {
            $this->assertArrayHasKey($key, $haystack);
            $this->assertEquals($value, $haystack[$key]);
        }
    }

    protected function assertTypesEquals(array $types, $haystack) {
        foreach ($types as $key => $type) {

            if(is_array($type)) {
                return $this->assertEqualTypes($type, $haystack[$key]);
            }

            if(is_string($type)) {
                $rules = [
                    'integer' => 'assertIsInt',
                    'float' => 'assertIsFloat',
                    'boolean' => 'assertIsBool',
                    'string' => 'assertIsString',
                    'array' => 'assertIsArray',
                    'null' => 'assertNull'
//                    'liv.*.dot' => 1,
//                    'li'=>[
//                        '*'=>[
//                            'name' => ['required', 'string']
//                        ]
//                ]
                ];

//                Validator::make([], [], []);


                if(!array_key_exists($type, $rules)) {
                    $this->throwException('Type not found in assertTypesEquals method');
                }


                $funcName = $rules[$type];

                $this->$funcName($haystack[$key], "typeof($key) not $type");
            }

        }
    }

}
