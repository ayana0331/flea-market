<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'item1.jpg',
                'condition_id' => 1,
                'categories' => ['ファッション','メンズ']
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'item2.jpg',
                'condition_id' => 2,
                'categories' => ['家電']
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => '',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'item3.jpg',
                'condition_id' => 3,
                'categories' => ['キッチン']
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'item4.jpg',
                'condition_id' => 4,
                'categories' => ['ファッション','メンズ']
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'image_path' => 'item5.jpg',
                'condition_id' => 1,
                'categories' => ['家電']
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => '',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'item6.jpg',
                'condition_id' => 2,
                'categories' => ['家電']
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'item7.jpg',
                'condition_id' => 3,
                'categories' => ['ファッション','レディース']
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => '',
                'description' => '使いやすいタンブラー',
                'image_path' => 'item8.jpg',
                'condition_id' => 4,
                'categories' => ['キッチン']
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_path' => 'item9.jpg',
                'condition_id' => 1,
                'categories' => ['キッチン']
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'image_path' => 'item10.jpg',
                'condition_id' => 2,
                'categories' => ['コスメ']
            ],
        ];

        foreach ($items as $data) {
            $item = Item::create([
                'user_id' => 1,
                'name' => $data['name'],
                'brand' => $data['brand'],
                'description' => $data['description'],
                'price' => $data['price'],
                'image_path' => $data['image_path'],
                'condition_id' => $data['condition_id'],
            ]);

            $categoryIds = DB::table('categories')
                ->whereIn('name', $data['categories'])
                ->pluck('id')
                ->toArray();

            $item->categories()->attach($categoryIds);
        }
    }
}
