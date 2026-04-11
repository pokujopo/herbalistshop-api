<?php

namespace App\Http\Controllers;
use App\Services\ZenopayService;

use Illuminate\Http\Request;

class betmakinController extends Controller
{
    public function betmakin(){
        return view('betmakin.betmakin');
    }

       protected $zenopay;

    public function __construct(ZenopayService $zenopay)
    {
        $this->zenopay = $zenopay;
    }

    public function makePayment(Request $request)
    {
        //$amount = 1000;
        //$phone = 255786584974;

        $data = [
               'amount' => $request->amount,
               'phone'  => $request->phone,
            // zingine kulingana na API ya ZenoPay
        ];

        $result = $this->zenopay->sendRequest('/payments', $data);

        return response()->json($result);
    }
}
