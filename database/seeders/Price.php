<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\OutletStock;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;


class Price extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $value = 'solid';
        $value2 = 'Formal Pant';
        $value3 = '2 Quarter';
        $value4 = 'Zara Blue';
        $value5 = 'Jogger';
        $value6 = 'Mobile Pant';
        $value7 = '2 In 1';
        $value8 = 'Gabardine Pant';
        $value9 = 'Printed Shirt';
        $value10 = 'Stripe';
        $value11 = 'A POLO';
        $value12 = 'B POLO';
        $value13 = 'China Polo';

        $products = Medicine::where('medicine_name', 'like', '%' . $value .'%')->get();

        foreach($products as $data){

            Medicine::where('id', $data->id)->where('medicine_name', $data->medicine_name)->update(['manufacturer_price'=>180]);

        }
        $id = Medicine::where('medicine_name', 'like', '%' . $value .'%')->pluck('id');
        $product2 = Medicine::where('category_id',1)->whereNotIn('id', $id)->get();
        foreach($product2 as $data2){
            Medicine::where('id', $data2->id)->where('medicine_name', $data2->medicine_name)->update(['manufacturer_price'=>205]);

        }
        $id2 = Medicine::where('medicine_name', 'like', '%' . $value .'%')->where('category_id',1)->pluck('id');
        $product3 = OutletStock::whereIn('medicine_id', $id2)->get();
        $product4 = OutletStock::whereNotIn('medicine_id', $id2)->get();

        foreach($product3 as $data3){
            WarehouseStock::where('medicine_id',$data3->medicine_id)->where('size',$data3->size)->update(['purchase_price'=>180]);
            OutletStock::where('medicine_id',$data3->medicine_id)->where('size',$data3->size)->update(['purchase_price'=>180]);
        }
        foreach($product4 as $data4){
            WarehouseStock::where('medicine_id',$data4->medicine_id)->where('size',$data4->size)->update(['purchase_price'=>205]);
            OutletStock::where('medicine_id',$data4->medicine_id)->where('size',$data4->size)->update(['purchase_price'=>205]);
        }
        $id3 = Medicine::where('medicine_name', $value2)->where('category_id',3)->pluck('id');
        $product5 = OutletStock::whereIn('medicine_id', $id3)->get();
        foreach($product5 as $data5){
            Medicine::where('id', $data5->medicine_id)->update(['manufacturer_price'=>480]);
            WarehouseStock::where('medicine_id',$data5->medicine_id)->where('size',$data5->size)->update(['purchase_price'=>480]);
            OutletStock::where('medicine_id',$data5->medicine_id)->where('size',$data5->size)->update(['purchase_price'=>480]);
        }
        $id4 = Medicine::where('medicine_name', $value3)->where('category_id',3)->pluck('id');
        $product6 = OutletStock::whereIn('medicine_id', $id4)->get();
        foreach($product6 as $data6){

                Medicine::where('id', $data6->medicine_id)->update(['manufacturer_price'=>250]);

            WarehouseStock::where('medicine_id',$data6->medicine_id)->where('size',$data6->size)->update(['purchase_price'=>250]);
            OutletStock::where('medicine_id',$data6->medicine_id)->where('size',$data6->size)->update(['purchase_price'=>250]);
        }
        $id5 = Medicine::where('medicine_name', $value4)->where('category_id',3)->pluck('id');
        $product7 = OutletStock::whereIn('medicine_id', $id5)->get();
        foreach($product7 as $data7){

                Medicine::where('id', $data7->medicine_id)->update(['manufacturer_price'=>500]);

            WarehouseStock::where('medicine_id',$data7->medicine_id)->where('size',$data7->size)->update(['purchase_price'=>500]);
            OutletStock::where('medicine_id',$data7->medicine_id)->where('size',$data7->size)->update(['purchase_price'=>500]);
        }
        $id6 = Medicine::where('medicine_name', $value5)->where('category_id',3)->pluck('id');
        $product8 = OutletStock::whereIn('medicine_id', $id6)->get();
        foreach($product8 as $data8){

                Medicine::where('id', $data8->medicine_id)->update(['manufacturer_price'=>400]);

            WarehouseStock::where('medicine_id',$data8->medicine_id)->where('size',$data8->size)->update(['purchase_price'=>400]);
            OutletStock::where('medicine_id',$data8->medicine_id)->where('size',$data8->size)->update(['purchase_price'=>400]);
        }
        $id7 = Medicine::where('medicine_name', $value6)->where('category_id',3)->pluck('id');
        $product9 = OutletStock::whereIn('medicine_id', $id7)->get();
        foreach($product9 as $data9){

                Medicine::where('id', $data9->medicine_id)->update(['manufacturer_price'=>400]);

            WarehouseStock::where('medicine_id',$data9->medicine_id)->where('size',$data9->size)->update(['purchase_price'=>400]);
            OutletStock::where('medicine_id',$data9->medicine_id)->where('size',$data9->size)->update(['purchase_price'=>400]);
        }
        $id8 = Medicine::where('medicine_name', $value7)->where('category_id',3)->pluck('id');
        $product10 = OutletStock::whereIn('medicine_id', $id8)->get();
        foreach($product10 as $data10){

                Medicine::where('id', $data10->medicine_id)->update(['manufacturer_price'=>650]);

            WarehouseStock::where('medicine_id',$data10->medicine_id)->where('size',$data10->size)->update(['purchase_price'=>650]);
            OutletStock::where('medicine_id',$data10->medicine_id)->where('size',$data10->size)->update(['purchase_price'=>650]);
        }
        $id9 = Medicine::where('medicine_name', $value8)->where('category_id',3)->pluck('id');
        $product11 = OutletStock::whereIn('medicine_id', $id9)->get();
        foreach($product11 as $data11){

                Medicine::where('id', $data11->medicine_id)->update(['manufacturer_price'=>480]);

            WarehouseStock::where('medicine_id',$data11->medicine_id)->where('size',$data11->size)->update(['purchase_price'=>480]);
            OutletStock::where('medicine_id',$data11->medicine_id)->where('size',$data11->size)->update(['purchase_price'=>480]);
        }
        $id10 = Medicine::where('medicine_name', $value9)->where('category_id',6)->pluck('id');
        $product12 = OutletStock::whereIn('medicine_id', $id10)->get();
        foreach($product12 as $data12){

                Medicine::where('id', $data12->medicine_id)->update(['manufacturer_price'=>450]);

            WarehouseStock::where('medicine_id',$data12->medicine_id)->where('size',$data12->size)->update(['purchase_price'=>450]);
            OutletStock::where('medicine_id',$data12->medicine_id)->where('size',$data12->size)->update(['purchase_price'=>450]);
        }
        $id11 = Medicine::where('medicine_name', $value10)->where('category_id',1)->pluck('id');
        $product13 = OutletStock::whereIn('medicine_id', $id11)->get();
        foreach($product13 as $data13){

                Medicine::where('id', $data13->medicine_id)->update(['manufacturer_price'=>120]);

            WarehouseStock::where('medicine_id',$data13->medicine_id)->where('size',$data13->size)->update(['purchase_price'=>120]);
            OutletStock::where('medicine_id',$data13->medicine_id)->where('size',$data13->size)->update(['purchase_price'=>120]);
        }
        $id12 = Medicine::where('medicine_name', $value11)->where('category_id',6)->pluck('id');
        $product14 = OutletStock::whereIn('medicine_id', $id12)->get();
        foreach($product14 as $data14){

                Medicine::where('id', $data14->medicine_id)->update(['manufacturer_price'=>630]);

            WarehouseStock::where('medicine_id',$data14->medicine_id)->where('size',$data14->size)->update(['purchase_price'=>630]);
            OutletStock::where('medicine_id',$data14->medicine_id)->where('size',$data14->size)->update(['purchase_price'=>630]);
        }
        $id13 = Medicine::where('medicine_name', $value12)->where('category_id',6)->pluck('id');
        $product15 = OutletStock::whereIn('medicine_id', $id13)->get();
        foreach($product15 as $data15){

                Medicine::where('id', $data15->medicine_id)->update(['manufacturer_price'=>250]);

            WarehouseStock::where('medicine_id',$data15->medicine_id)->where('size',$data15->size)->update(['purchase_price'=>250]);
            OutletStock::where('medicine_id',$data15->medicine_id)->where('size',$data15->size)->update(['purchase_price'=>250]);
        }
        $id14 = Medicine::where('medicine_name', $value13)->where('category_id',1)->pluck('id');
        $product16 = OutletStock::whereIn('medicine_id', $id14)->get();
        foreach($product16 as $data16){

                Medicine::where('id', $data16->medicine_id)->update(['manufacturer_price'=>500]);

            WarehouseStock::where('medicine_id',$data16->medicine_id)->where('size',$data16->size)->update(['purchase_price'=>500]);
            OutletStock::where('medicine_id',$data16->medicine_id)->where('size',$data16->size)->update(['purchase_price'=>500]);
        }
    }


}
