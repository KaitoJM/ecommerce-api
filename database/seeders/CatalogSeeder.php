<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductSpecification;
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
        $pams1 = $this->seedProductAttributes($iphone->id, $storage->id, '128GB');
        $pams2 = $this->seedProductAttributes($iphone->id, $storage->id, '256GB');
        $pamc1 = $this->seedProductAttributes($iphone->id, $color->id, 'Ultramarine', '#0437F2');
        $pamc2 = $this->seedProductAttributes($iphone->id, $color->id, 'Teal', '#008080');
        $pamc3 = $this->seedProductAttributes($iphone->id, $color->id, 'Pink', '#FFC0CB');
        $pamc4 = $this->seedProductAttributes($iphone->id, $color->id, 'White', '#FFFFFF');
        $pamc5 = $this->seedProductAttributes($iphone->id, $color->id, 'Black', '#000000');
        $palr1 = $this->seedProductAttributes($macbook->id, $ram->id, '16GB Unified Memory');
        $palr2 = $this->seedProductAttributes($macbook->id, $ram->id, '24GB Unified Memory');
        $pals1 = $this->seedProductAttributes($macbook->id, $storage->id, '512GB SSD Storage');
        $pals2 = $this->seedProductAttributes($macbook->id, $storage->id, '1TB SSD Storage');
        $palc1 = $this->seedProductAttributes($macbook->id, $color->id, 'Space Black', '#121212');
        $palc2 = $this->seedProductAttributes($macbook->id, $color->id, 'Silver', '#C0C0C0');

        $iphoneCombinations = [
            [
                'price' => 49990.00,
                'product_id' => $iphone->id,
                'combinations' => [
                    [$pams1->id, $pamc1->id], //128GB Storage, Ultramarine
                    [$pams1->id, $pamc2->id], //128GB Storage, Teal
                    [$pams1->id, $pamc3->id], //128GB Storage, Pink
                    [$pams1->id, $pamc4->id], //128GB Storage, White
                    [$pams1->id, $pamc5->id], //128GB Storage, Black
                ],
            ],
            [
                'price' => 57990.00,
                'product_id' => $iphone->id,
                'combinations' => [
                    [$pams2->id, $pamc1->id], //256GB Storage, Ultramarine
                    [$pams2->id, $pamc2->id], //256GB Storage, Teal
                    [$pams2->id, $pamc3->id], //256GB Storage, Pink
                    [$pams2->id, $pamc4->id], //256GB Storage, White
                    [$pams2->id, $pamc5->id], //256GB Storage, Black
                ],
            ]
            
        ];

        $macbookCombinations = [
            [
                'price' => 99990.00,
                'product_id' => $macbook->id,
                'combinations' => [
                    [$palr1->id, $pals1->id, $palc1->id], //16GB Unified Memory, 512GB SSD Storage, Space Black
                    [$palr1->id, $pals1->id, $palc2->id], //16GB Unified Memory, 512GB SSD Storage, Silver
                ],
            ],
            [
                'price' => 112990.00,
                'product_id' => $macbook->id,
                'combinations' => [
                    [$palr1->id, $pals2->id, $palc1->id], //16GB Unified Memory, 1TB SSD Storage, Space Black
                    [$palr1->id, $pals2->id, $palc2->id], //16GB Unified Memory, 1TB SSD Storage, Silver
                ],
            ],
            // This combination do not exist
            // [
            //     'price' => 112990.00,
            //      'product_id' => $macbook->id,
            //     'combinations' => [
            //         [$palr2->id, $pals1->id, $palc1->id], //24GB Unified Memory, 512GB SSD Storage, Space Black
            //         [$palr2->id, $pals1->id, $palc2->id], //24GB Unified Memory, 512GB SSD Storage, Silver
            //     ],
            // ],
            [
                'price' => 125990.00,
                'product_id' => $macbook->id,
                'combinations' => [
                    [$palr2->id, $pals2->id, $palc1->id], //24GB Unified Memory, 1TB SSD Storage, Space Black
                    [$palr2->id, $pals2->id, $palc2->id], //24GB Unified Memory, 1TB SSD Storage, Silver
                ],
            ],
        ];

        // seed product specification
        foreach ($iphoneCombinations as $mk => $mv) {
            $price = $mv['price'];
            $product_id = $mv['product_id'];

            foreach ($mv['combinations'] as $mck => $mcv) {
                $this->seedProductSpecification($product_id, implode(',', $mcv), $price, 100);
            }
        }

        foreach ($macbookCombinations as $lk => $lv) {
            $price = $lv['price'];
            $product_id = $lv['product_id'];

            foreach ($lv['combinations'] as $lck => $lcv) {
                $this->seedProductSpecification($product_id, implode(',', $lcv), $price, 100);
            }
        }

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
        $productAttribute = ProductAttribute::create([
            'product_id' => $product_id,
            'attribute_id' => $attribute_id,
            'value' => $value,
            'color_value' => $colorValue,
        ]);
        
        return $productAttribute;
    }

    private function seedProductSpecification($product_id, $combination, $price, $stock) {
        ProductSpecification::create([
            'product_id' => $product_id,
            'combination' => $combination,
            'price' => $price,
            'stock' => $stock
        ]);
    }
}
