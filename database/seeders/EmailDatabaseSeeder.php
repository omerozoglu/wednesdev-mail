<?php

namespace Wednesdev\Mail\Database\Seeders;

use Illuminate\Database\Seeder;

class EmailDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $this->call([
            EmailTemplateSeeder::class,
        ]);
    }
}
