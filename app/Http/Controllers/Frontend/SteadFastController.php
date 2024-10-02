<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use App\Models\Order;
use App\Models\Orderdetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;
use Session;
use Http;

class SteadFastController extends Controller
{
    private $base_url = 'https://portal.steadfast.com.bd/api/v1';

    public function bulkCreateInit($ids){

        $orders = Order::whereIn('id', $ids)->get();
        $data = array();

        foreach($orders as $order){
            $item = [
                'invoice' => $order->invoice_no,
                'recipient_name' => $order->name ? $order->name : 'N/A',
                'recipient_address' => $order->address ? $order->address : 'N/A',
                'recipient_phone' => $order->phone ? $order->phone : '',
                'cod_amount' => $order->grand_total,
                'note' => $order->comment,
            ];

            array_push($data, $item);
        }


        //$steadfast = new Steadfast();
        $result = $this->bulkCreate(json_encode($data));

        return $result;

    }


    public function bulkCreate($data){
        $api_key = 'nu1hga30cr2qa2r1oqtbktmmt4zn1ivx';
        $secret_key = '6ytjwmqzlfbj8ko9bede9q9z';

        $response = Http::withHeaders([
            'Api-Key' => $api_key,
            'Secret-Key' => $secret_key,
            'Content-Type' => 'application/json'
        ])->post($this->base_url.'/create_order/bulk-order', [
            'data' => $data,

        ]);

        return json_decode($response->getBody()->getContents());
    }

}
