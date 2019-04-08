<?php

use Illuminate\Database\Seeder;

class OptionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Option::firstOrCreate([
            'key'  => 'uk_warehouse_address_line_1',
            'type' => '1'
        ], [
            'value' => '123 Green Lane Drive'
        ]);
        \App\Models\Option::firstOrCreate([
            'key'  => 'uk_warehouse_address_line_2',
            'type' => '1'
        ], [
            'value' => 'London PR3 INJ'
        ]);
        \App\Models\Option::firstOrCreate([
            'key'  => 'uk_warehouse_country',
            'type' => '1'
        ], [
            'value' => 'United Kingdom'
        ]);
    }
}
