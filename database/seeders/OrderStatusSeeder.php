<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = $this->getStatuses();

        foreach ($statuses as $statusKey => $status) {
            OrderStatus::create([
                'status' => $status['status'],
                'color_code' => $status['color_code'],
                'description' => $status['description'],
            ]);
        }
    }

    private function getStatuses() {
        return [
            [
                'status' => 'Pending',
                'color_code' => 'blue',
                'description' => 'Awaiting payment'
            ],
            [
                'status' => 'Paid',
                'color_code' => 'green',
                'description' => 'Payment successfully received'
            ],
            [
                'status' => 'Payment Failed',
                'color_code' => 'red',
                'description' => 'Payment attempt failed'
            ],
            [
                'status' => 'Processing',
                'color_code' => 'green',
                'description' => 'Order confirmed, being prepared'
            ],
            [
                'status' => 'Packed',
                'color_code' => 'green',
                'description' => 'Items packed and ready for shipment'
            ],
            [
                'status' => 'Shipped',
                'color_code' => 'yellow',
                'description' => 'Handed over to courier'
            ],
            [
                'status' => 'In Transit',
                'color_code' => 'yellow',
                'description' => 'In delivery network'
            ],
            [
                'status' => 'Out For Delivery',
                'color_code' => 'yellow',
                'description' => 'Courier is delivering today'
            ],
            [
                'status' => 'Delivered',
                'color_code' => 'green',
                'description' => 'Successfully delivered'
            ],
            [
                'status' => 'On Hold',
                'color_code' => 'orange',
                'description' => 'Manual review needed'
            ],
            [
                'status' => 'Cancelled',
                'color_code' => 'red',
                'description' => 'Cancelled before shipping'
            ],
            [
                'status' => 'Completed',
                'color_code' => 'green',
                'description' => 'Order finalized, no further actions'
            ],
            [
                'status' => 'Return Requested',
                'color_code' => 'orange',
                'description' => 'Customer requested return'
            ],
            [
                'status' => 'Returned',
                'color_code' => 'orange',
                'description' => 'Items returned'
            ],
            [
                'status' => 'Refunded',
                'color_code' => 'orange',
                'description' => 'Payment refunded'
            ]
        ];
    }
}
