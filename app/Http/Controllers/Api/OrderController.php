<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('items')->paginate(10);
        return OrderResource::collection($orders)->additional([
            'status' => true,
            'total' => $orders->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $data = $request->validated();

        $userId = auth()->id();
        $cartItems = CartItem::with('product')->where('user_id',$userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error'=>'Cart is empty'],422);
        }

        foreach ($cartItems as $ci) {
            if ($ci->product->stock < $ci->quantity) {
                return response()->json([
                    'error' => "Product {$ci->product->name} has insufficient stock"
                ], 422);
            }
        }


        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => strtoupper(Str::random(10)),
                'user_id' => $userId,
                'address' => $data['address'],
                'phone' => $data['phone'],
                'total' => 0,
            ]);

            $total = 0;
            foreach ($cartItems as $ci) {
                $product = Product::lockForUpdate()->find($ci->product_id); 
                if ($product->stock < $ci->quantity) {
                    DB::rollBack();
                    return response()->json(['error'=>"Product {$product->name} out of stock"],422);
                }
                $product->stock -= $ci->quantity;
                $product->save(); 

                $subtotal = $product->price * $ci->quantity;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $ci->quantity,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $order->total = $total;
            $order->save();

            CartItem::where('user_id',$userId)->delete();

            DB::commit();

            return response()->json(new OrderResource($order), 201);;

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error'=>'Server Error','message'=>$e->getMessage()],500);
        }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order = Order::with('items')->where('user_id', auth()->id())->findOrFail($id);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
