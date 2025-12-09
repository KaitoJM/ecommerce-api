<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // categories
        [$laptop, $mobilePhones, $tablets] = $this->seedCategories();

        // attributes
        [$size, $color] = $this->seedAttributes();

    }

    private function seedCategories() {
        $laptop = Category::create([
            'name' => 'Laptop',
        ]);

        $mobilePhones = Category::create([
            'name' => 'Mobile Phones',
        ]);

        $tablets = Category::create([
            'name' => 'Tablets',
        ]);

        return [$laptop, $mobilePhones, $tablets];
    }

    private function seedAttributes() {
        $size = Attribute::create([
            'attribute' => 'Size',
        ]);

        $color = Attribute::create([
            'attribute' => 'Color',
        ]);

        return [$size, $color];
    }

    private function seedProducts() {
        $iphone = Product::create([
            'name' => 'Iphone 16 128GB'
        ]);
    }
}
