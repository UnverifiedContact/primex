<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

use Database\Seeders\FixtureProductSeeder;
use Database\Seeders\FixtureStockSeeder;

class ProductTest extends TestCase
{
    public static function setUpBeforeClass() : void
    {
        parent::setUpBeforeClass();
    }

    public function setup() : void
    {
        parent::setup();
        $this->seed(FixtureProductSeeder::class);
        $this->seed(FixtureStockSeeder::class);
    }

    public function test_show()
    {
        $response = $this->get('/api/product/382026');
        $body = $response->getData(true);
        $code = $response->getStatusCode();

        $expected = [
            "data" => [
                "id" => 18,
                "code" => '382026',
                "name" => "PS-INSIDE VP QM",
                "description" => "PS-INSIDE VP QM"
            ]
        ];

        $this->assertEquals(200, $code);
        $this->assertSame($expected, $body);
    }

    public function test_show_with_stock()
    {
        $response = $this->get('/api/product/382026?stock');
        $body = $response->getData(true);
        $code = $response->getStatusCode();

        $expected = [
            "data" => [
                "id" => 18,
                "code" => '382026',
                "name" => "PS-INSIDE VP QM",
                "description" => "PS-INSIDE VP QM",
                "stock_on_hand" => 8
            ]
        ];

        $this->assertEquals(200, $code);
        $this->assertEquals($expected, $body);
    }

    public function test_show_404()
    {
        $response = $this->get('/api/product/999999?stock');
        $code = $response->getStatusCode();
        $this->assertEquals(404, $code);
    }

    public function test_store()
    {
        $body = [
            'code' => '44444',
            'name' => 'New Meat Product',
            'description' => 'New Meat Product',
        ];

        $expected = [
            "data" => [
                "id" => 21,
                "code" => '44444',
                "name" => "New Meat Product",
                "description" => "New Meat Product"
            ]
        ];

        $response = $this->post('/api/product', $body);
        $body = $response->getData(true);
        $code = $response->getStatusCode();

        $this->assertEquals(201, $code);
        $this->assertSame($expected, $body);

        # get the new entity
        $response = $this->get('/api/product/44444');
        $body = $response->getData(true);
        $code = $response->getStatusCode();

        $this->assertEquals(200, $code);
        $this->assertSame($expected, $body);
    }

    public function test_destroy()
    {
        $response = $this->delete('/api/product/49354');
        $body = $response->getContent();
        $code = $response->getStatusCode();

        $this->assertEquals(204, $code);
        $this->assertEmpty($body);

        # again to confirm it's done
        $response = $this->delete('/api/product/49354');
        $body = $response->getContent();
        $code = $response->getStatusCode();

        $this->assertEquals(404, $code);
    }

    public function test_update()
    {
        $body = [
            'name' => 'Best Wonderful Product',
            'description' => 'New Wonderful Product',
        ];

        $response = $this->patch('/api/product/49354', $body);
        $code = $response->getStatusCode();
        $body = $response->getData(true);

        $expected = [
            "data" => [
                "id" => 19,
                "code" => "49354",
                "name" => "Best Wonderful Product",
                "description" => "New Wonderful Product"
            ]
        ];

        $this->assertEquals(200, $code);
        $this->assertSame($expected, $body);
    }

    public function test_index()
    {
        $response = $this->get('/api/product?stock&available&order=DESC');
        $body = $response->getData(true);
        $code = $response->getStatusCode();

        $expected = [
            [
                "id" => 18,
                "code" => "382026",
                "name" => "PS-INSIDE VP QM",
                "description" => "PS-INSIDE VP QM",
                "stock_on_hand" => 8
            ]
        ];

        $this->assertEquals(200, $code);
        $this->assertEquals($expected, $body['data']);
    }

}