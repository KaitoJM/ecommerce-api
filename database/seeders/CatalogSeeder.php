<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
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
        [$ram, $storage, $color] = $this->seedAttributes();
        
        // products
        [$iphone, $macbook] = $this->seedProducts();

        // prroduct categories
        $iphone->categories()->sync([$mobilePhones->id]);
        $macbook->categories()->sync([$laptop->id]);

        // prroduct attributes
        $this->seedProductAttributes($iphone->id, $storage->id, '128GB');
        $this->seedProductAttributes($iphone->id, $storage->id, '256GB');
        $this->seedProductAttributes($iphone->id, $color->id, 'Ultramarine', '#0437F2');
        $this->seedProductAttributes($iphone->id, $color->id, 'Teal', '#008080');
        $this->seedProductAttributes($iphone->id, $color->id, 'Pink', '#FFC0CB');
        $this->seedProductAttributes($iphone->id, $color->id, 'White', '#FFFFFF');
        $this->seedProductAttributes($iphone->id, $color->id, 'Black', '#000000');
        $this->seedProductAttributes($macbook->id, $ram->id, '16GB Unified Memory');
        $this->seedProductAttributes($macbook->id, $ram->id, '24GB Unified Memory');
        $this->seedProductAttributes($macbook->id, $storage->id, '512GB SSD Storage');
        $this->seedProductAttributes($macbook->id, $storage->id, '1TB SSD Storage');
        $this->seedProductAttributes($macbook->id, $color->id, 'Space Black', '#121212');
        $this->seedProductAttributes($macbook->id, $color->id, 'Silver', '#C0C0C0');


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
        $ram = Attribute::create([
            'attribute' => 'RAM',
        ]);

        $storage = Attribute::create([
            'attribute' => 'Storage',
        ]);

        $color = Attribute::create([
            'attribute' => 'Color',
        ]);

        return [$ram, $storage, $color];
    }

    private function seedProducts() {
        $iphone = Product::create([
            'name' => 'Iphone 16 128GB',
            'published' => true
        ]);

        $macbook = Product::create([
            'name' => 'MacBook Pro 14-inch 512GB SSD',
            'published' => true
        ]);

        return [$iphone, $macbook];
    }

    private function seedProductAttributes($product_id, $attribute_id, $value, $colorValue = null) {
        ProductAttribute::create([
            'product_id' => $product_id,
            'attribute_id' => $attribute_id,
            'value' => $value,
            'color_value' => $colorValue,
        ]);
    }
}
