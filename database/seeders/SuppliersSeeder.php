<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Global Electronics Supplier', 'contact_email' => 'supplier1@globelect.com', 'phone_number' => '021-1234-5678', 'company_name' => 'Global Electronics Ltd', 'contact_person' => 'Alex Chen', 'address' => 'Jl. Industri No. 1, Jakarta'],
            ['name' => 'Fashion Forward Supply', 'contact_email' => 'orders@fashionforward.com', 'phone_number' => '021-2345-6789', 'company_name' => 'Fashion Forward Inc', 'contact_person' => 'Maria Santos', 'address' => 'Jl. Mode No. 2, Bandung'],
            ['name' => 'Food & Beverage Distributors', 'contact_email' => 'sales@fbdist.com', 'phone_number' => '031-3456-7890', 'company_name' => 'F&B Distributors Co', 'contact_person' => 'Robert Kim', 'address' => 'Jl. Makanan No. 3, Surabaya'],
            ['name' => 'Home Garden Supplies', 'contact_email' => 'info@homegarden.com', 'phone_number' => '022-4567-8901', 'company_name' => 'Home & Garden Co', 'contact_person' => 'Jenny Liu', 'address' => 'Jl. Taman No. 4, Bandung'],
            ['name' => 'Sports Equipment Co', 'contact_email' => 'orders@sportsequip.com', 'phone_number' => '061-5678-9012', 'company_name' => 'Sports Equipment Ltd', 'contact_person' => 'Mike Johnson', 'address' => 'Jl. Olahraga No. 5, Medan'],
            ['name' => 'Tech Gadgets Wholesale', 'contact_email' => 'wholesale@techgadgets.com', 'phone_number' => '024-6789-0123', 'company_name' => 'Tech Gadgets Inc', 'contact_person' => 'Lisa Wang', 'address' => 'Jl. Teknologi No. 6, Semarang'],
            ['name' => 'Beauty Products Supplier', 'contact_email' => 'supply@beautyproducts.com', 'phone_number' => '0274-7890-1234', 'company_name' => 'Beauty Products Co', 'contact_person' => 'Sarah Brown', 'address' => 'Jl. Kecantikan No. 7, Yogyakarta'],
            ['name' => 'Automotive Parts Supply', 'contact_email' => 'parts@autoparts.com', 'phone_number' => '0341-8901-2345', 'company_name' => 'Auto Parts Ltd', 'contact_person' => 'Tom Wilson', 'address' => 'Jl. Otomotif No. 8, Malang'],
            ['name' => 'Office Supplies Direct', 'contact_email' => 'direct@officesupplies.com', 'phone_number' => '0271-9012-3456', 'company_name' => 'Office Supplies Co', 'contact_person' => 'Emma Davis', 'address' => 'Jl. Kantor No. 9, Solo'],
            ['name' => 'Toy World Distributor', 'contact_email' => 'distributor@toyworld.com', 'phone_number' => '0361-0123-4567', 'company_name' => 'Toy World Inc', 'contact_person' => 'Jack Miller', 'address' => 'Jl. Mainan No. 10, Denpasar'],
            ['name' => 'Jewelry & Accessories Co', 'contact_email' => 'co@jewelry.com', 'phone_number' => '0411-1234-5678', 'company_name' => 'Jewelry Co Ltd', 'contact_person' => 'Grace Lee', 'address' => 'Jl. Perhiasan No. 11, Makassar'],
            ['name' => 'Pet Supplies Central', 'contact_email' => 'central@petsupplies.com', 'phone_number' => '0711-2345-6789', 'company_name' => 'Pet Supplies Inc', 'contact_person' => 'David Park', 'address' => 'Jl. Hewan No. 12, Palembang'],
            ['name' => 'Music Instruments Hub', 'contact_email' => 'hub@musicinstruments.com', 'phone_number' => '0542-3456-7890', 'company_name' => 'Music Hub Ltd', 'contact_person' => 'Anna Zhang', 'address' => 'Jl. Musik No. 13, Balikpapan'],
            ['name' => 'Hardware Tools Supply', 'contact_email' => 'supply@hardwaretools.com', 'phone_number' => '0541-4567-8901', 'company_name' => 'Hardware Tools Co', 'contact_person' => 'Chris Taylor', 'address' => 'Jl. Perkakas No. 14, Samarinda'],
            ['name' => 'Baby & Kids Products', 'contact_email' => 'products@babykids.com', 'phone_number' => '0761-5678-9012', 'company_name' => 'Baby & Kids Co', 'contact_person' => 'Michelle Wong', 'address' => 'Jl. Anak No. 15, Pekanbaru'],
            ['name' => 'Arts & Crafts Supplier', 'contact_email' => 'supplier@artscrafts.com', 'phone_number' => '0741-6789-0123', 'company_name' => 'Arts & Crafts Ltd', 'contact_person' => 'Kevin Martinez', 'address' => 'Jl. Seni No. 16, Jambi'],
            ['name' => 'Industrial Equipment Co', 'contact_email' => 'co@industrial.com', 'phone_number' => '0736-7890-1234', 'company_name' => 'Industrial Co Ltd', 'contact_person' => 'Rachel Green', 'address' => 'Jl. Industri No. 17, Bengkulu'],
            ['name' => 'Travel Luggage Supply', 'contact_email' => 'supply@travelluggage.com', 'phone_number' => '0721-8901-2345', 'company_name' => 'Travel Supply Co', 'contact_person' => 'Daniel Kim', 'address' => 'Jl. Perjalanan No. 18, Lampung'],
            ['name' => 'Furniture Warehouse', 'contact_email' => 'warehouse@furniture.com', 'phone_number' => '0561-9012-3456', 'company_name' => 'Furniture Co Ltd', 'contact_person' => 'Sandra Lopez', 'address' => 'Jl. Furnitur No. 19, Pontianak'],
            ['name' => 'Kitchen Dining Supplies', 'contact_email' => 'supplies@kitchendining.com', 'phone_number' => '0511-0123-4567', 'company_name' => 'Kitchen Dining Co', 'contact_person' => 'Brian White', 'address' => 'Jl. Dapur No. 20, Banjarmasin'],
        ];

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->insert(array_merge($supplier, [
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
