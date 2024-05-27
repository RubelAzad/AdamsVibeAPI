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
    
public function index(Request $request)
{
    try {
        // Fetch top 10 product inventory IDs of the current month based on delivered orders
        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('order_items.inventory_id', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->where('orders.order_status_id', 4) // Only delivered orders
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->groupBy('order_items.inventory_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->pluck('order_items.inventory_id')
            ->toArray();

        $query = Inventory::query();
        $query->with(['product', 'inventoryVariants', 'inventoryImages'])
            ->where('status', Inventory::STATUS_ACTIVE)
            ->where(function($query) {
                $query->where(function($query) {
                        $query->where('is_vendor', '=', '2')
                              ->where('is_pre_order', '=', '2');
                    })
                    ->orWhere(function($query) {
                        $query->whereNull('is_vendor')
                              ->WhereNull('is_pre_order');
                    });
            });

        // Only include the top 10 products if we have them
        if (!empty($topProducts)) {
            $query->whereIn('id', $topProducts);
        }

        $query->when($request->order_column && $request->order_by, function ($q) use ($request) {
            $q->orderBy($request->order_column, $request->order_by);
        });

        $query->when($request->limit, function ($q) use ($request) {
            $q->limit($request->limit);
        });

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
