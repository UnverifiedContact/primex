<?php

namespace Database\Seeders;
use JeroenZwart\CsvSeeder\CsvSeeder;

class ProductSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = base_path() . '/database/seed_data/' . 'primex-products-test.csv';
        $this->tablename = 'products';
        $this->mapping = ['code', 'name', 'description'];
        $this->header = true;
        $this->delimiter = ',';
    }
}
