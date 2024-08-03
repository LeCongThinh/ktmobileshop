<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreOrderRequest $request)
    // {
    //     //
    // }

    public function store(StoreOrderRequest $request)
    {
        foreach (Cart::content() as $item) {
            if ($item->qty > 5) {
                return redirect()->back()->with('error', 'Đặt hàng với số lượng mỗi sản phẩm không quá 5');
            }
        }
        try
        {
            //Lưu vào bảng đơn hàng
            $characterSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $order = Order::create([
                'code' => substr(str_shuffle($characterSet), 0, 10),
                'username' => $request->username,
                'phone' => $request->phone,
                'email' => $request->email,
                'delivery_method' => $request->delivery,
                'province_id' => $request->delivery == 'home' ? $request->province_id : null,
                'district_id' => $request->delivery == 'home' ? $request->district_id : null,
                'ward_id' => $request->delivery == 'home' ? $request->ward_id : null,
                'address' => $request->delivery == 'home' ? $request->address : null,
                'total' => Cart::subtotal(0, '', ''), //Tổng giá trị của đơn hàng
            ]);

            // Lưu vào bảng chi tiết đơn hàng
            foreach (Cart::content() as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'product_image' => $item->options->image,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->subtotal, //Tổng giá trị của từng sản phẩm
                ]);
            }
            // Clear giỏ hàng
            Cart::destroy();
            // Reset giỏ hàng
            session()->forget('cartItemsCount');
            // Thành công
            return redirect()->route('viewPayment', ['orderId' => $order->code])->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đặt hàng không thành công: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
