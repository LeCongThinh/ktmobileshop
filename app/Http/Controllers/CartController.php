<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        // Kiểm tra nếu giỏ hàng trống
        if (Cart::count() == 0) {
            return view('shopping-cart.blank-cart');
        }
        $provinces = DB::table('provinces')->orderBy('name', 'ASC')->get();
        return view('shopping-cart.cart-index', ['cartItems' => Cart::content(), 'provinces' => $provinces]);
    }
    public function fetchDistricts($province_id = null)
    {
        $districts = DB::table('districts')->where("province_id", $province_id)->get();

        return response()->json(['districts' => $districts]);
    }
    public function fetchWards($district_id = null)
    {
        $wards = DB::table('wards')->where("district_id", $district_id)->get();

        return response()->json(['wards' => $wards]);
    }
    public function addToCart(Request $request)
    {
        //Thêm sản phẩm vào giỏ hàng
        $productId = $request->input('id');
        $cartItem = Cart::content()->where('id', $productId)->first();

        //Mỗi sản phẩm thêm tối đa là 5 sản phẩm
        if ($cartItem && $cartItem->qty >= 5) {
            return response()->json(['error' => 'Số lượng mỗi sản phẩm có thể thêm tối đa là 5.'], 400);
        }
        Cart::add([
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'qty' => $request->input('qty'),
            'options' => [
                'image' => $request->input('image'),
            ],
        ]);

        // Cập nhật cartItemsCount trong session
        $cartItemsCount = Cart::count();
        Session::put('cartItemsCount', $cartItemsCount);

        if ($request->ajax()) {
            return response()->json(['success' => 'Đã thêm ' . $request->input('name') . ' vào giỏ hàng!', 'cartItems' => Cart::content(), 'cartItemsCount' => $cartItemsCount]);
        }
        return redirect()->back()->with('success', 'Đã thêm ' . $request->input('name') . ' vào giỏ hàng!');
    }

    public function removeFromCart($rowId, Request $request)
    {
        Cart::remove($rowId);

        // Cập nhật cartItemsCount trong session
        $cartItemsCount = Cart::count();
        Session::put('cartItemsCount', $cartItemsCount);

        if ($request->ajax()) {
            return response()->json(['success' => 'Đã xóa sản phẩm!', 'cartItems' => Cart::content(), 'cartItemsCount' => $cartItemsCount]);
        }

        return redirect()->route('indexCart')->with('success', 'Đã xóa sản phẩm!');
    }

    public function updateQuantity(Request $request, $rowId)
    {
        try {
            Cart::update($rowId, $request->input('quantity'));
            // Cập nhật lại cartItemsCount trong session
            $cartItemsCount = Cart::count();
            Session::put('cartItemsCount', $cartItemsCount);
            if ($request->ajax()) {
                $updatedItem = Cart::get($rowId);
                $formattedPrice = number_format($updatedItem->price, 2, ',', '.') . ' đ';
                $formattedSubtotal = number_format($updatedItem->subtotal, 2, ',', '.') . ' đ';

                // Tính lại tổng số tiền dựa trên subtotal của các mặt hàng trong giỏ hàng
                $total = Cart::subtotal();

                return response()->json([
                    'success' => 'Product quantity updated successfully!',
                    'updatedItem' => $updatedItem,
                    'price' => $formattedPrice,
                    'subtotal' => $formattedSubtotal,
                    'total' => $total,
                    'cartItemsCount' => $cartItemsCount,
                ]);
            }

            return redirect()->route('indexCart')->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating product quantity.'], 500);
        }
    }

}