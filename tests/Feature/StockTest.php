<?php

namespace Tests\Feature;

use Database\Seeders\FixtureStockSeeder;
use Database\Seeders\FixtureProductSeeder;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class StockTest extends TestCase
{
    public static function setUpBeforeClass() : void
    {
        parent::setUpBeforeClass();
    }

    public function setup() : void
    {
        parent::setup();
        $this->seed(FixtureStockSeeder::class);
    }

    public function test_store()
    {
        $body = [
            'on_hand' => 100,
            'taken' => 0,
            'production_date' => '11/11/2022',
        ];

        $expected = [
            "data" => [
                "id" => 38,
                "product_code" => '49354',
                "on_hand" => 100,
                "taken" => 0,
                "production_date" => "11/11/2022"
            ]
        ];

        $stock_before = $this->get('/api/product/49354?stock')->getData()->data->stock_on_hand;

        $response = $this->post('/api/product/49354/stock', $body);
        $body = $response->getData(true);
        $code = $response->getStatusCode();

        $this->assertEquals(201, $code);
        $this->assertSame($expected, $body);

        $stock_after = $this->get('/api/product/49354?stock')->getData()->data->stock_on_hand;

        $this->assertTrue($stock_after == ($stock_before + 100));
    }

    public function test_index()
    {
        $response = $this->get('/api/product/382026/stock');
        $code = $response->getStatusCode();
        $body = $response->getData(true);

        $expected = [
            [
                "id" => 1,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ],
            [
                "id" => 2,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ],
            [
                "id" => 3,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ],
            [
                "id" => 4,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ],
            [
                "id" => 5,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ],
            [
                "id" => 6,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ],
            [
                "id" => 7,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ],
            [
                "id" => 31,
                "product_code" => "382026",
                "on_hand" => 1,
                "taken" => 0,
                "production_date" => "26/08/2020"
            ]
        ];

        $this->assertEquals(200, $code);
        $this->assertEquals($expected, $body['data']);
    }

    public function test_index_404()
    {
        $response = $this->get('/api/product/999999/stock');
        $code = $response->getStatusCode();

        $this->assertEquals(404, $code);
    }

    public function test_index_empty()
    {
        $response = $this->get('/api/product/49354/stock');
        $code = $response->getStatusCode();
        $body = $response->getData(true);

        $this->assertEquals(200, $code);
        $this->assertEquals([], $body['data']);
    }

}