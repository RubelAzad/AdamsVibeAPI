<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use App\Models\InventoryVariant;
use Exception;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductRankingController extends Controller
{
    
//     public function index(Request $request)
// {
//     try {
//         // Dates for current month
//         $currentMonthStart = Carbon::now()->startOfMonth()->toDateString();
//         $currentMonthEnd = Carbon::now()->endOfMonth()->toDateString();

//         // Dates for previous month
//         $previousMonthStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
//         $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth()->toDateString();

//         // Fetch top 5 product inventory IDs of the current month
//         $currentMonthTopProducts = DB::table('order_items')
//             ->join('orders', 'order_items.order_id', '=', 'orders.id')
//             ->select('order_items.inventory_id', DB::raw('SUM(order_items.quantity) as total_quantity'))
//             ->where('orders.order_status_id', 4) // Only delivered orders
//             ->whereBetween('orders.order_date', [$currentMonthStart, $currentMonthEnd])
//             ->groupBy('order_items.inventory_id')
//             ->orderBy('total_quantity', 'desc')
//             ->limit(5) // Limit to top 5 products
//             ->pluck('order_items.inventory_id')
//             ->toArray();

        

//         // Fetch top 5 product inventory IDs of the previous month
//         $previousMonthTopProducts = DB::table('order_items')
//             ->join('orders', 'order_items.order_id', '=', 'orders.id')
//             ->select('order_items.inventory_id', DB::raw('SUM(order_items.quantity) as total_quantity'))
//             ->where('orders.order_status_id', 4) // Only delivered orders
//             ->whereBetween('orders.order_date', [$previousMonthStart, $previousMonthEnd])
//             ->groupBy('order_items.inventory_id')
//             ->orderBy('total_quantity', 'desc')
//             ->limit(5) // Limit to top 5 products
//             ->pluck('order_items.inventory_id')
//             ->toArray();

//         // Merge top product IDs from both months and limit to top 10 unique IDs
//         $topProducts = array_slice(array_unique(array_merge($currentMonthTopProducts, $previousMonthTopProducts)), 0, 10);

//         $query = Inventory::query();
//         $query->with(['product', 'inventoryVariants', 'inventoryImages'])
//             ->where('status', Inventory::STATUS_ACTIVE)
//             ->where(function($query) {
//                 $query->where(function($query) {
//                         $query->where('is_vendor', '=', '2')
//                             ->where('is_pre_order', '=', '2');
//                     })
//                     ->orWhere(function($query) {
//                         $query->whereNull('is_vendor')
//                             ->WhereNull('is_pre_order');
//                     });
//             });

//         // Only include the top 10 products if we have them
//         if (!empty($topProducts)) {
//             $query->whereIn('id', $topProducts);
//         } else {
//             // If there are no top products, return an empty collection
//             return collect();
//         }

//         // Apply ordering and limit based on the request
//         $query->when($request->order_column && $request->order_by, function ($q) use ($request) {
//             $q->orderBy($request->order_column, $request->order_by);
//         });

//         $query->when($request->limit, function ($q) use ($request) {
//             $q->limit($request->limit);
//         });

//         // Handle pagination
//         if ($request->paginate === 'yes') {
//             return $query->paginate($request->get('limit', 15));
//         } else {
//             return $query->get();
//         }
//     } catch (Exception $exception) {
//         return make_error_response($exception->getMessage());
//     }
// }


public function index(Request $request)
{
    try {
        // Dates for previous month
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        // Fetch top 10 product inventory IDs of the previous month
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('order_items.inventory_id', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->where('orders.order_status_id', 4) // Only delivered orders
            ->whereBetween('orders.order_date', [$previousMonthStart, $previousMonthEnd])
            ->groupBy('order_items.inventory_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10) // Limit to top 10 products
            ->pluck('order_items.inventory_id')
            ->toArray();

        $query = Inventory::query();
        $query->with(['product', 'inventoryVariants', 'inventoryImages'])
            ->where('status', Inventory::STATUS_ACTIVE)
            ->where(function($query) {
                $query->where(function($query) {
                        $query->whereNull('is_vendor')
                            ->whereNull('is_pre_order');
                    })
                    ->orWhere(function($query) {
                        $query->where('is_vendor', '!=', '1')
                            ->where('is_pre_order', '!=', '1');
                    });
            });

        // Only include the top 10 products if we have them
        if (!empty($topProducts)) {
            $query->whereIn('id', $topProducts);
        } else {
            // If there are no top products, return an empty collection
            return collect();
        }

        // Apply ordering and limit based on the request
        $query->when($request->order_column && $request->order_by, function ($q) use ($request) {
            $q->orderBy($request->order_column, $request->order_by);
        });

        $query->when($request->limit, function ($q) use ($request) {
            $q->limit($request->limit);
        });

        // Handle pagination
        if ($request->paginate === 'yes') {
            return $query->paginate($request->get('limit', 15));
        } else {
            return $query->get();
        }
    } catch (Exception $exception) {
        return make_error_response($exception->getMessage());
    }
}



public function wholesaleTop(Request $request)
{
    try {
        // Dates for previous month
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        // Fetch top 10 product inventory IDs of the previous month
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('order_items.inventory_id', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->where('orders.order_status_id', 4) // Only delivered orders
            ->whereBetween('orders.order_date', [$previousMonthStart, $previousMonthEnd])
            ->groupBy('order_items.inventory_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10) // Limit to top 10 products
            ->pluck('order_items.inventory_id')
            ->toArray();


        $query = Inventory::query();
        $query->with(['product', 'inventoryVariants', 'inventoryImages'])
            ->where('status', Inventory::STATUS_ACTIVE)
            ->where(function($query) {
                $query->where('is_vendor', '=', '1')
                    ->orWhere(function($query) {
                        $query->where('is_pre_order', '=', '1')
                            ->whereDate('pre_start', '<=', now())
                            ->whereDate('pre_end', '>=', now());
                    });
            });
           
            

        // Only include the top 10 products if we have them
        if (!empty($topProducts)) {
            $query->whereIn('id', $topProducts);
            

            
        } else {
            // If there are no top products, return an empty collection
            return collect();
        }

        // Apply ordering and limit based on the request
        $query->when($request->order_column && $request->order_by, function ($q) use ($request) {
            $q->orderBy($request->order_column, $request->order_by);
        });

        $query->when($request->limit, function ($q) use ($request) {
            $q->limit($request->limit);
        });

        // Handle pagination
        if ($request->paginate === 'yes') {
            return $query->paginate($request->get('limit', 15));
        } else {
            return $query->get();
        }
    } catch (Exception $exception) {
        return make_error_response($exception->getMessage());
    }
}

   
}
