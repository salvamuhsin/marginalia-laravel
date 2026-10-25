<?php
namespace Database\Seeders;
use App\Models\{Category, Product};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder {
    private function image(string $title): ?string {
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $path = 'products/' . Str::slug($title) . '.' . $ext;
            if (Storage::disk('public')->exists($path)) return $path;
        }
        return null;
    }

    public function run(): void {
        if (Category::exists()) { $this->command?->warn('Categories already exist, skipping sample data.'); return; }

        $book = [
            ['Fiction', '📖', [
                ['Pride and Prejudice', 'Jane Austen', 1813, 899, '#7A1F3D'],
                ['The Great Gatsby', 'F. Scott Fitzgerald', 1925, 1050, '#0F5C5C'],
                ['One Hundred Years of Solitude', 'Gabriel García Márquez', 1967, 1399, '#B5532A'],
                ['Things Fall Apart', 'Chinua Achebe', 1958, 999, '#3B4A1F']]],
            ['Science', '🔭', [
                ['A Brief History of Time', 'Stephen Hawking', 1988, 1299, '#14213D'],
                ['Cosmos', 'Carl Sagan', 1980, 1499, '#2B2D6E'],
                ['The Selfish Gene', 'Richard Dawkins', 1976, 1199, '#5A3E1B']]],
            ['History', '🏛️', [
                ['Sapiens', 'Yuval Noah Harari', 2011, 1699, '#8C6A00'],
                ['Guns, Germs, and Steel', 'Jared Diamond', 1997, 1599, '#444B54']]],
            ['Technology', '💻', [
                ['The Pragmatic Programmer', 'Andrew Hunt & David Thomas', 1999, 4499, '#1F3A5F'],
                ['Clean Code', 'Robert C. Martin', 2008, 3999, '#2E6B3A'],
                ['Designing Data-Intensive Applications', 'Martin Kleppmann', 2017, 5499, '#6B2E2E']]],
        ];
        $acc = [
            ['Bookmarks', '🔖', [
                ['Leather Bookmark Set (3)', 'Hand-stitched genuine leather bookmarks in three colours.', 1200, '#8A5A2B'],
                ['Brass Page Clip Bookmarks', 'Slim brass clips that hold your page without damaging it.', 900, '#B08D2E'],
                ['Magnetic Bookmarks (6 pack)', 'Colourful magnetic markers that never fall out.', 700, '#7A1F3D']]],
            ['Bookshelves', '📚', [
                ['Floating Wall Bookshelf', 'Solid wood wall shelf with a hidden mounting rail.', 4900, '#5A3E1B'],
                ['Tabletop Oak Bookshelf', 'Compact oak shelf for desks and bedside tables.', 3500, '#8C6A00'],
                ['Rolling Book Cart', 'Three-tier cart on wheels, perfect for reading corners.', 8900, '#444B54']]],
            ['Book Holders', '📖', [
                ['Adjustable Wooden Book Stand', 'Folding stand with page clips and five angle settings.', 2400, '#3B4A1F'],
                ['Hands-free Page Holder', 'Soft silicone holder for reading in bed.', 1500, '#2B2D6E'],
                ['Marble Bookends (pair)', 'Heavy marble bookends that keep shelves tidy.', 3200, '#444B54']]],
            ['Book Lights', '💡', [
                ['Clip-on Rechargeable Book Light', 'USB-C rechargeable, three warm brightness levels.', 1800, '#B5532A'],
                ['Neck Reading Light', 'Flexible wearable light, ideal for night reading.', 2200, '#0F5C5C'],
                ['Warm LED Reading Lamp', 'Eye-friendly desk lamp with a dimmer.', 3900, '#14213D']]],
        ];
        foreach ($book as [$name, $icon, $items]) {
            $c = Category::create(['name' => $name, 'slug' => Str::slug($name), 'type' => 'book', 'icon' => $icon]);
            foreach ($items as [$t, $a, $y, $p, $col]) $c->products()->save(new Product([
                'category_id' => $c->id, 'type' => 'book', 'title' => $t, 'author' => $a, 'year' => $y, 'price' => $p,
                'stock' => rand(3, 25), 'cover_color' => $col, 'isbn' => '978' . random_int(1000000000, 9999999999),
                'image' => $this->image($t),
                'description' => "{$t} by {$a}. Edit this description from the admin panel.",
            ]));
        }
        foreach ($acc as [$name, $icon, $items]) {
            $c = Category::create(['name' => $name, 'slug' => Str::slug($name), 'type' => 'accessory', 'icon' => $icon]);
            foreach ($items as [$t, $d, $p, $col]) $c->products()->save(new Product([
                'category_id' => $c->id, 'type' => 'accessory', 'title' => $t, 'description' => $d,
                'price' => $p, 'stock' => rand(5, 40), 'cover_color' => $col, 'image' => $this->image($t),
            ]));
        }
    }
}
