<?php

namespace Database\Seeders;

use App\Models\EquipmentBrand;
use App\Models\EquipmentCategory;
use App\Models\EquipmentItem;
use App\Models\EquipmentModel;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['name' => 'Máy tính', 'slug' => 'may-tinh', 'description' => 'Máy tính bàn và laptop'],
            ['name' => 'Máy chiếu', 'slug' => 'may-chieu', 'description' => 'Máy chiếu cho các cuộc họp'],
            ['name' => 'Thiết bị âm thanh', 'slug' => 'thiet-bi-am-thanh', 'description' => 'Loa, micro, âm ly'],
            ['name' => 'Camera', 'slug' => 'camera', 'description' => 'Camera quay video và chụp ảnh'],
            ['name' => 'Thiết bị viễn thông', 'slug' => 'thiet-bi-vien-thong', 'description' => 'Điện thoại, fax'],
        ];

        foreach ($categories as $cat) {
            EquipmentCategory::create($cat);
        }

        // Create Brands
        $brands = [
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Lenovo', 'slug' => 'lenovo'],
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Epson', 'slug' => 'epson'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'Canon', 'slug' => 'canon'],
            ['name' => 'Nikon', 'slug' => 'nikon'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'LG', 'slug' => 'lg'],
        ];

        foreach ($brands as $brand) {
            EquipmentBrand::create($brand);
        }

        // Create Equipment Models
        $models = [
            ['category_id' => 1, 'brand_id' => 1, 'name' => 'Dell Inspiron 15', 'description' => 'Laptop Dell màn hình 15 inch'],
            ['category_id' => 1, 'brand_id' => 2, 'name' => 'HP Pavilion 14', 'description' => 'Laptop HP nhẹ nhàng, di động'],
            ['category_id' => 1, 'brand_id' => 3, 'name' => 'Lenovo ThinkPad X1', 'description' => 'Laptop business cao cấp'],
            ['category_id' => 1, 'brand_id' => 4, 'name' => 'MacBook Pro 14', 'description' => 'Laptop Apple chuyên nghiệp'],
            ['category_id' => 2, 'brand_id' => 5, 'name' => 'Epson EB-2250U', 'description' => 'Máy chiếu 1080p chuyên dụng'],
            ['category_id' => 4, 'brand_id' => 6, 'name' => 'Sony Alpha A6400', 'description' => 'Camera chuyên nghiệp'],
            ['category_id' => 4, 'brand_id' => 7, 'name' => 'Canon EOS 90D', 'description' => 'Camera DSLR quay video'],
            ['category_id' => 4, 'brand_id' => 8, 'name' => 'Nikon Z9', 'description' => 'Camera mirrorless cao cấp'],
            ['category_id' => 1, 'brand_id' => 9, 'name' => 'Samsung 24 inch', 'description' => 'Màn hình Samsung Full HD'],
            ['category_id' => 1, 'brand_id' => 10, 'name' => 'LG Ultrawide 34', 'description' => 'Màn hình ultra wide LG'],
        ];

        foreach ($models as $model) {
            EquipmentModel::create($model);
        }

        // Create Equipment Items
        $equipmentItems = [
            ['model_id' => 1, 'serial_number' => 'DELL001', 'status' => 'available', 'purchase_date' => '2023-01-15', 'purchase_cost' => 12000000],
            ['model_id' => 1, 'serial_number' => 'DELL002', 'status' => 'available', 'purchase_date' => '2023-01-15', 'purchase_cost' => 12000000],
            ['model_id' => 2, 'serial_number' => 'HP001', 'status' => 'available', 'purchase_date' => '2023-02-10', 'purchase_cost' => 10000000],
            ['model_id' => 2, 'serial_number' => 'HP002', 'status' => 'available', 'purchase_date' => '2023-02-10', 'purchase_cost' => 10000000],
            ['model_id' => 3, 'serial_number' => 'LEN001', 'status' => 'available', 'purchase_date' => '2023-03-20', 'purchase_cost' => 18000000],
            ['model_id' => 4, 'serial_number' => 'MAC001', 'status' => 'available', 'purchase_date' => '2023-04-05', 'purchase_cost' => 35000000],
            ['model_id' => 5, 'serial_number' => 'PROJ001', 'status' => 'available', 'purchase_date' => '2023-05-12', 'purchase_cost' => 25000000],
            ['model_id' => 5, 'serial_number' => 'PROJ002', 'status' => 'maintenance', 'purchase_date' => '2023-05-12', 'purchase_cost' => 25000000],
            ['model_id' => 6, 'serial_number' => 'CAM001', 'status' => 'available', 'purchase_date' => '2023-06-01', 'purchase_cost' => 22000000],
            ['model_id' => 7, 'serial_number' => 'CAM002', 'status' => 'available', 'purchase_date' => '2023-06-15', 'purchase_cost' => 28000000],
        ];

        foreach ($equipmentItems as $item) {
            EquipmentItem::create($item);
        }
    }
}
