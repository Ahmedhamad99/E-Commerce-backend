<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Http\Resources\CartItemResource;
use App\Http\Requests\CartItemRequest;
class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = CartItem::with('product')->where('user_id',auth()->id())->paginate(10);
        return CartItemResource::collection($items) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function add(CartItemRequest $request)
    {
        $data = $request->validated();
        $product = Product::findOrFail($data['product_id']);
        if ($product->stock < $data['quantity']) {
            return response()->json(['error'=>'Not enough stock'],422);
        }

        $item = CartItem::updateOrCreate(
            ['user_id'=>auth()->id(),'product_id'=>$product->id],
            ['quantity'=>$data['quantity']]
        );

        return response()->json(new CartItemResource($item),201);
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function remove(Product $product)
    {
        $item = CartItem::where('user_id',auth()->id())->where('product_id',$product->id)->firstOrFail();
        $item->delete();
        
        return response()->json(['message'=>'Removed from cart']);
    }
}
