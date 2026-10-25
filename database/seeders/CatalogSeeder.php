<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/catalog.json');
        if (! file_exists($file) || Category::exists()) return;

        $data = json_decode(file_get_contents($file), true);

        foreach (array_chunk($data['categories'], 50) as $rows) DB::table('categories')->insert($rows);
        foreach (array_chunk($data['products'], 50) as $rows) DB::table('products')->insert($rows);
        foreach (array_chunk($data['pivot'], 50) as $rows) DB::table($data['pivot_table'])->insert($rows);
    }
}