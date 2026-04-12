<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Example of using PostgreSQL date functions
        $data = DB::table('orders')
            ->selectRaw("EXTRACT(MONTH FROM order_date) AS month, EXTRACT(YEAR FROM order_date) AS year, TO_CHAR(order_date, 'YYYY-MM-DD HH24:MI:SS') AS formatted_date")
            ->groupByRaw('EXTRACT(MONTH FROM order_date), EXTRACT(YEAR FROM order_date)')
            ->get();

        return view('admin.dashboard', compact('data'));
    }
}