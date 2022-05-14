<?php

namespace Database\Seeders;
use JeroenZwart\CsvSeeder\CsvSeeder;
use Carbon\Carbon;

class FixtureStockSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = base_path() . '/database/seed_data/' . 'primex-stock-test-lite.csv';
        $this->tablename = 'stocks';
        $this->mapping = ['product_code', 'on_hand', 'production_date'];
        $this->header = true;
        $this->delimiter = ',';
        $this->parsers = ['production_date' => function ($value) {
            return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }];
    }
}