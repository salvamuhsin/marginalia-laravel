<?php
namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportCatalog extends Command
{
    protected $signature = 'shop:export';
    protected $description = 'Export categories, products and their links to database/seeders/data/catalog.json';

    public function handle(): int
    {
        $pivot = (new Product)->categories()->getTable();

        $data = [
            'categories'  => DB::table('categories')->get()->map(fn ($r) => (array) $r)->all(),
            'products'    => DB::table('products')->get()->map(fn ($r) => (array) $r)->all(),
            'pivot_table' => $pivot,
            'pivot'       => DB::table($pivot)->get()->map(fn ($r) => (array) $r)->all(),
        ];

        $dir = database_path('seeders/data');
        if (! is_dir($dir)) mkdir($dir, 0755, true);

        file_put_contents(
            $dir . '/catalog.json',
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $this->info(count($data['categories']) . ' categories, ' . count($data['products']) . ' products, '
            . count($data['pivot']) . ' category links exported.');
        return 0;
    }
}