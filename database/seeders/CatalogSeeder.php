<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
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
        [$laptop, $mobilePhones, $electronics, $audio, $computer] = $this->seedCategories();

        // brands
        [$apple, $samsung, $sony, $dell, $hp, $lenovo, $google, $bose, $jbl] = $this->seedBrands();

        // attributes
        [$ram, $storage, $color] = $this->seedAttributes();

        $products = [
            [
                'name' => 'Iphone 16 128GB',
                'summary' => "The latest iPhone with advanced features.",
                'image' => 'https://d1rlzxa98cyc61.cloudfront.net/catalog/product/cache/1801c418208f9607a371e61f8d9184d9/1/7/177270_2020.jpg',
                'price' => 49990.00,
                'published' => true,
                'brand_id' => $apple->id,
                'stock' => 50,
                'categories' => [$mobilePhones->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $storage->id,
                        'values' => ['128GB', '256GB']
                    ],
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Ultramarine', 'Teal', 'Pink', 'White', 'Black']
                    ]
                ]
            ],
            [
                'name' => 'MacBook Pro 14-inch 512GB SSD',
                'summary' => "Powerful performance in a compact design.",
                'image' => 'https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/mbp14-spacegray-select-202110?wid=904&hei=840&fmt=jpeg&qlt=80&.v=1632799176000',
                'price' => 99990.00,
                'published' => true,
                'brand_id' => $apple->id,
                'stock' => 25,
                'categories' => [$laptop->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $ram->id,
                        'values' => ['16GB Unified Memory', '24GB Unified Memory']
                    ],
                    [
                        'attribute_id' => $storage->id,
                        'values' => ['512GB SSD Storage', '1TB SSD Storage']
                    ],
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Space Black', 'Silver']
                    ]
                ]
            ],
            [
                'name' => 'Samsung Galaxy S21',
                'summary' => "High-end Android smartphone with great performance.",
                'image' => 'https://d1rlzxa98cyc61.cloudfront.net/catalog/product/cache/1801c418208f9607a371e61f8d9184d9/1/7/174359_2020_5.jpg',
                'price'=> 39990.00,
                'published' => true,
                'brand_id' => $samsung->id,
                'stock' => 40,
                'categories' => [$mobilePhones->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $storage->id,
                        'values' => ['128GB', '256GB']
                    ],
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Phantom Gray', 'Phantom White', 'Phantom Violet']
                    ]
                ]
            ],
            [
                'name' => 'Sony WH-1000XM4',
                'summary' => "Industry-leading noise canceling headphones.",
                'image' => 'https://m.media-amazon.com/images/I/71o8Q5XJS5L._AC_SL1500_.jpg',
                'price' => 19990.00,
                'published' => true,
                'brand_id' => $sony->id,
                'stock' => 75,
                'categories' => [$audio->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Black', 'Silver']
                    ]
                ]
            ],
            [
                'name' => 'Dell XPS 13',
                'summary' => "Compact and powerful laptop for professionals.",
                'image' => 'https://m.media-amazon.com/images/I/710EGJBdIML._AC_SL1500_.jpg',
                'price' => 89990.00,
                'published' => true,
                'brand_id' => $dell->id,
                'stock' => 30,
                'categories' => [$laptop->id, $computer->id],
                'attributes' => [
                    [
                        'attribute_id' => $ram->id,
                        'values' => ['8GB', '16GB']
                    ],
                    [
                        'attribute_id' => $storage->id,
                        'values' => ['256GB SSD', '512GB SSD']
                    ],
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Silver', 'White']
                    ]
                ]
            ],
            [
                'name' => 'Apple AirPods Pro',
                'summary' => "Wireless earbuds with active noise cancellation.",
                'image' => 'https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/MWP22?wid=2000&hei=2000&fmt=jpeg&qlt=80&.v=1591634795000',
                'price' => 12990.00,
                'published' => true,
                'brand_id' => $apple->id,
                'stock' => 100,
                'categories' => [$audio->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $color->id,
                        'values' => ['White']
                    ]
                ]
            ],
            [
                'name' => 'HP Spectre x360',
                'summary' => "Versatile 2-in-1 laptop with sleek design.",
                'image' => 'https://www.hp.com/content/dam/sites/worldwide/personal-computers/consumer/laptops-and-2-n-1s/spectre/version-2023/HP%20Spectre%20x360%2014__Mobile@2x.png',
                'price' => 109990.00,
                'published' => true,
                'brand_id' => $hp->id,
                'stock' => 20,
                'categories' => [$laptop->id, $computer->id],
                'attributes' => [
                    [
                        'attribute_id' => $ram->id,
                        'values' => ['16GB', '32GB']
                    ],
                    [
                        'attribute_id' => $storage->id,
                        'values' => ['512GB SSD', '1TB SSD']
                    ],
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Nightfall Black', 'Poseidon Blue']
                    ]
                ]
            ],
            [
                'name' => 'Google Pixel 6',
                'summary' => "Google's flagship smartphone with excellent camera.",
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSBLJwRZpjEu6iae1VsI82j_UM7UMAV36hZ6w&s',
                'price' => 34990.00,
                'published' => true,
                'brand_id' => $google->id,
                'stock' => 60,
                'categories' => [$mobilePhones->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $storage->id,
                        'values' => ['128GB', '256GB']
                    ],
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Stormy Black', 'Kinda Coral', 'Sorta Seafoam']
                    ]
                ]
            ],
            [
                'name' => 'Bose QuietComfort 35 II',
                'summary' => "Comfortable headphones with world-class noise cancellation.",
                'image' => 'https://assets.bose.com/content/dam/Bose_DAM/Web/consumer_electronics/global/products/headphones/qc35_ii/product_silo_images/qc35_ii_black_EC_hero.psd/_jcr_content/renditions/cq5dam.web.320.320.png',
                'price' => 17990.00,
                'brand_id' => $bose->id,
                'published' => true,
                'stock' => 80,
                'categories' => [$audio->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Black', 'Silver']
                    ]
                ]
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'summary' => "Durable and lightweight laptop for business users.",
                'image' => 'https://p3-ofp.static.pub//fes/cms/2024/07/05/05dhzg0lrtq4i0d3wxqyjjakwmbmzr331426.png',
                'price' => 119990.00,
                'published' => true,
                'brand_id' => $lenovo->id,
                'stock' => 15,
                'categories' => [$laptop->id, $computer->id],
                'attributes' => [
                    [
                        'attribute_id' => $ram->id,
                        'values' => ['16GB', '32GB']
                    ],
                    [
                        'attribute_id' => $storage->id,
                        'values' => ['512GB SSD', '1TB SSD']
                    ],
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Black']
                    ]
                ]
            ],
            [
                'name' => 'JBL Charge 4',
                'summary' => "Portable Bluetooth speaker with powerful sound.",
                'image' => 'https://www.jbl.com.ph/on/demandware.static/-/Sites-masterCatalog_Harman/default/dw8d795c7a/JBL_Charge4_Front_Midnight_Black_1605x1605px.png',
                'price' => 8990.00,
                'brand_id' => $jbl->id,
                'published' => true,
                'stock' => 120,
                'categories' => [$audio->id, $electronics->id],
                'attributes' => [
                    [
                        'attribute_id' => $color->id,
                        'values' => ['Black', 'Blue', 'Red']
                    ]
                ]
            ]
        ];

        // products
        $this->seedProducts($products);
    }

    private function seedBrands() {
        $apple = Brand::create(
            [
                'name' => 'Apple',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg'
            ]
        );

        $samsung = Brand::create(
            [
                'name' => 'Samsung',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/6/61/Samsung_old_logo_before_year_2015.svg'
            ]
        );

        $sony = Brand::create(
            [
                'name' => 'Sony',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Sony_logo.svg/2560px-Sony_logo.svg.png'
            ]
        );

        $dell = Brand::create(
            [
                'name' => 'Dell',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Dell_Logo.svg/1200px-Dell_Logo.svg.png'
            ]
        );

        $hp = Brand::create(
            [
                'name' => 'HP',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/HP_logo_2012.svg/2048px-HP_logo_2012.svg.png'
            ]
        );

        $lenovo = Brand::create(
            [
                'name' => 'HP',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Branding_lenovo-logo_lenovologoposred_low_res.png/1200px-Branding_lenovo-logo_lenovologoposred_low_res.png'
            ]
        );

        $google = Brand::create(
            [
                'name' => 'Google',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Google_Favicon_2025.svg/250px-Google_Favicon_2025.svg.png'
            ]
        );

        $bose = Brand::create(
            [
                'name' => 'Bose',
                'image' => 'https://1000logos.net/wp-content/uploads/2021/05/Bose-logo.png'
            ]
        );

        $jbl = Brand::create(
            [
                'name' => 'JBL',
                'image' => 'https://cdn.sistemawbuy.com.br/arquivos/d65810bb9f73806217af761bbf1b7313/marcas/logo-jbl-66c3987b9f3c6.png'
            ]
        );

        return [$apple, $samsung, $sony, $dell, $hp, $lenovo, $google, $bose, $jbl];
    }

    private function seedCategories() {
        $laptop = Category::create([
            'name' => 'Laptop',
            'description' => 'High-performance laptops designed for work, study, and entertainment. Explore a wide range of models featuring powerful processors, ample storage, vibrant displays, and portable designs to meet everyday computing and professional needs.'
        ]);

        $mobilePhones = Category::create([
            'name' => 'Mobile Phones',
            'description' => 'Discover the latest smartphones with advanced features, powerful performance, and sleek designs. From high-quality cameras to long-lasting batteries, find mobile phones built for communication, productivity, and entertainment on the go.'
        ]);

        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Explore a wide range of electronic products designed to enhance everyday life. From smart devices and home appliances to personal gadgets and accessories, find reliable technology that combines innovation, performance, and convenience.'
        ]);

        $audio = Category::create([
            'name' => 'Audio',
            'description' => 'Experience rich, high-quality sound with our range of audio devices. From headphones and speakers to sound systems and accessories, discover products designed to deliver clear audio, deep bass, and immersive listening for every lifestyle.'
        ]);

        $computer = Category::create([
            'name' => 'Computers',
            'description' => 'Powerful and reliable computers built for productivity, creativity, and everyday use. Browse desktops, all-in-one PCs, and accessories designed to deliver fast performance, smooth multitasking, and dependable computing for home, office, and professional needs.'
        ]);

        return [$laptop, $mobilePhones, $electronics, $audio, $computer];
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

    private function seedProducts($products) {
        foreach ($products as $productData) {
            $product = Product::factory()->create([
                'name' => $productData['name'],
                'summary' => $productData['summary'],
                'published' => $productData['published'],
            ]);

            // Seed product image
            if (isset($productData['image'])) {
                $this->seedProductImages($product->id, $productData['image']);
            }

            // Attach categories
            if (isset($productData['categories'])) {
                $product->categories()->sync($productData['categories']);
            }

            // Attach attributes
            if (isset($productData['attributes'])) {
                foreach ($productData['attributes'] as $attr) {
                    $attribute_id = $attr['attribute_id'];
                    foreach ($attr['values'] as $value) {
                        $this->seedProductAttributes($product->id, $attribute_id, $value);
                    }
                }
            }

            $possibleCombinations = [[]];
            foreach ($productData['attributes'] as $attr) {
                $attribute_id = $attr['attribute_id'];
                $newCombinations = [];
                foreach ($attr['values'] as $value) {
                    foreach ($possibleCombinations as $combination) {
                        $newCombinations[] = array_merge($combination, [$attribute_id => $value]);
                    }
                }
                $possibleCombinations = $newCombinations;
            }

            // Seed product specifications
            foreach ($possibleCombinations as $index => $combination) {
                $combinationArr = [];
                foreach ($combination as $attr_id => $value) {
                    $combinationArr[] = [
                        'attribute_id' => $attr_id,
                        'value' => $value,
                    ];
                }
                $isDefault = ($index === 0);


                $this->seedProductSpecification($product->id, json_encode($combinationArr), $productData['price'], $productData['stock'], $isDefault);
            }
        }
    }

    private function seedProductImages($product_id, $imageUrl) {
        ProductImage::create([
            'product_id' => $product_id,
            'source' => $imageUrl,
            'cover' => true,
        ]);
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

    private function seedProductSpecification($product_id, $combination, $price, $stock, $default = false) {
        ProductSpecification::create([
            'product_id' => $product_id,
            'combination' => $combination,
            'price' => $price,
            'stock' => $stock,
            'default' => $default,
        ]);
    }
}
