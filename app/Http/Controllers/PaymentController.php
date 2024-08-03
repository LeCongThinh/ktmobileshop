<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //Sau khi thanh toán thành công đường dẫn trả về có dạng
    //  http://127.0.0.1:8000/?
    // partnerCode=MOMOBKUN20180529
    // &orderId=6848
    // &requestId=1719303620
    // &amount=10000
    // &orderInfo=Thanh+to%C3%A1n+qua+MoMo
    // &orderType=momo_wallet
    // &transId=4068908694
    // &resultCode=0
    // &message=Successful.
    // &payType=napas
    // &responseTime=1719303652867
    // &extraData=
    // &signature=85eb0ad973bdc67a8299c39d246e97936342f5ea2b990612a42dc9ccc28b04f0
    // &paymentOption=momo

    protected function fixImage($orderDetails)
    {
        foreach ($orderDetails as $detail) {
            // Tìm kiếm sản phẩm dựa trên id của sản phẩm được lưu trong bảng order_detail
            $product = Product::find($detail->product_id);
            if ($product && $product->image && Storage::disk('public')->exists($product->image)) {
                $detail->product->image = Storage::url($product->image);
            } else {
                $detail->product->image = asset('/image/no-pictures.png');
            }
        }
    }
    public function viewPayment($orderId)
    {
        // Lấy thông tin đơn hàng vừa đặt
        $order = DB::table('orders')
            ->leftJoin('provinces', 'orders.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'orders.district_id', '=', 'districts.id')
            ->leftJoin('wards', 'orders.ward_id', '=', 'wards.id')
            ->select('orders.*', 'provinces.name as province_name', 'districts.name as district_name', 'wards.name as ward_name')
            ->where('orders.code', $orderId)
            ->first();

        // dd($order);
        // Lấy chi tiết đơn hàng
        $orderDetails = OrderDetail::where('order_id', $order->id)->get();
        $this->fixImage($orderDetails);

        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('payment.view-payment', compact('order', 'orderDetails', 'orderId'));
    }

    public function viewPaymentFail($orderId)
    {
        // Lấy thông tin đơn hàng vừa đặt
        $order = DB::table('orders')
            ->leftJoin('provinces', 'orders.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'orders.district_id', '=', 'districts.id')
            ->leftJoin('wards', 'orders.ward_id', '=', 'wards.id')
            ->select('orders.*', 'provinces.name as province_name', 'districts.name as district_name', 'wards.name as ward_name')
            ->where('orders.code', $orderId)
            ->first();
            //dd($order);
            
        // Lấy chi tiết đơn hàng
        $orderDetails = OrderDetail::where('order_id', $order->id)->get();
        $this->fixImage($orderDetails);

        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('payment.payment-fail', compact('order', 'orderDetails', 'orderId'));
    }
    public function MoMo(Request $request, $orderId)
    {
        $order = DB::table('orders')
            ->leftJoin('provinces', 'orders.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'orders.district_id', '=', 'districts.id')
            ->leftJoin('wards', 'orders.ward_id', '=', 'wards.id')
            ->select('orders.*', 'provinces.name as province_name', 'districts.name as district_name', 'wards.name as ward_name')
            ->where('orders.code', $orderId)
            ->first();
            //dd($order);
        // Kiểm tra nếu không tìm thấy đơn hàng
        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }
        // Lấy code và amount từ request
        $code = $request->input('code');
        $amount = $request->input('amount');

        // Xử lý thanh toán qua MoMo
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        // Tạo orderId kết hợp số ngẫu nhiên với chuỗi cố định và code
        $orderId = 'ORD-' . $code . '-' . strtoupper(uniqid());

        $orderInfo = "Thanh toán $orderId qua mã MoMo QR";
        $redirectUrl = route('callbackMoMo', ['orderId' => $order->code]); // URL callback để MoMo gửi kết quả giao dịch
        $ipnUrl = route('callbackMoMo', ['orderId' => $order->code]); // IPN URL để MoMo gửi thông báo về giao dịch
        $extraData = "";

        $requestId = time() . "";
        $requestType = "captureWallet";

        // Đảm bảo rằng amount là chuỗi số nguyên
        $amount = (string) (int) $amount;

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'KTMobile Shop',
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId, // Sử dụng orderId đã tạo
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        $resultCode = $jsonResult['resultCode'];
        // dd($resultCode);
        if ($resultCode == 22) {
            // Người dùng nhấn nút quay về hoặc giao dịch bị hủy
            return redirect()->route('viewPayment', ['orderId' => $order->code])->with('cancel-payment', 'TỔNG TIỀN ĐƠN HÀNG VƯỢT QUÁ HẠN MỨC GIAO DỊCH CỦA MOMO QR!');
        }
        if (isset($jsonResult['payUrl'])) {
            // Chuyển hướng đến trang thanh toán của MoMo
            return redirect()->away($jsonResult['payUrl']);
        } else {
            // Xử lý khi thanh toán thất bại hoặc bị hủy
            return redirect()->route('viewPayment', ['orderId' => $order->code]);
        }
    }

    public function handleMoMoCallback(Request $request, $orderId)
    {
        $order = DB::table('orders')
            ->leftJoin('provinces', 'orders.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'orders.district_id', '=', 'districts.id')
            ->leftJoin('wards', 'orders.ward_id', '=', 'wards.id')
            ->select('orders.*', 'provinces.name as province_name', 'districts.name as district_name', 'wards.name as ward_name')
            ->where('orders.code', $orderId)
            ->first();
            //dd($order);
        // Lấy dữ liệu từ query string (GET request) hoặc body (POST request)
        $jsonResult = $request->all();

        // Kiểm tra nếu có resultCode
        if (isset($jsonResult['resultCode'])) {
            $resultCode = $jsonResult['resultCode'];
            // Kiểm tra trạng thái giao dịch bằng 0 là thành công
            if ($resultCode == 0) {
                $orderId = $jsonResult['orderId'];

                // Tách phần giữa mã đơn hàng từ orderId
                $orderIdParts = explode('-', $orderId);
                $orderIdFromMoMo = $orderIdParts[1];
                $order = Order::where('code', $orderIdFromMoMo)->first();
                if ($order) {
                    $order->payment_method = 'momo';
                    $order->status = 1;
                    $order->save();
                }
                // Thanh toán thành công
                return redirect()->route('homepage')->with('payment-success', 'Đã thanh toán đơn hàng thành công!');

            } elseif ($resultCode == 1006) {
                // Người dùng nhấn nút quay về hoặc giao dịch bị hủy
                return redirect()->route('viewPaymentFail', ['orderId' => $order->code])->with('cancel-payment', 'THANH TOÁN ONLINE BẰNG MOMO QR CODE KHÔNG THÀNH CÔNG!');
            } else {
                // Giao dịch thất bại
                return redirect()->route('viewPaymentFail', ['orderId' => $order->code]);
            }
        } else {
            // Không có resultCode trong phản hồi từ MoMo
            return redirect()->route('viewPaymentFail', ['orderId' => $order->code]);
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function Vnpay(Request $request, $orderId)
    {
        $order = DB::table('orders')
            ->leftJoin('provinces', 'orders.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'orders.district_id', '=', 'districts.id')
            ->leftJoin('wards', 'orders.ward_id', '=', 'wards.id')
            ->select('orders.*', 'provinces.name as province_name', 'districts.name as district_name', 'wards.name as ward_name')
            ->where('orders.code', $orderId)
            ->first();
        // Lấy code và amount từ request
        $code = $request->input('code');
        $amount = $request->input('amount');
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpayReturn', ['orderId' => $order->code]);
        //Thông tin thanh toán trong email: 0306201491@caothang.edu.vn
        $vnp_TmnCode = "5YJDWX0V";
        $vnp_HashSecret = "KF55RDDKKDFCJVQFWUSD0XMTBZ2X24UH";
        $vnp_TxnRef = $code;
        $vnp_OrderInfo = 'Thanh toán hóa đơn KTMobile';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        // Lấy IP từ request
        $vnp_IpAddr = $request->ip();
        // Tạo thời gian hết hạn giao dịch là 5 phút sau hiện tại
        $vnp_CreateDate = Carbon::now()->format('YmdHis');
        $vnp_ExpireDate = Carbon::now()->addMinutes(5)->format('YmdHis');
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        // $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        // dd($vnp_ResponseCode);
        // dd($vnp_Url);

        // Chuyển hướng đến URL thanh toán
        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request, $orderId)
    {
        // dd($request->all());
        $order = DB::table('orders')
            ->leftJoin('provinces', 'orders.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'orders.district_id', '=', 'districts.id')
            ->leftJoin('wards', 'orders.ward_id', '=', 'wards.id')
            ->select('orders.*', 'provinces.name as province_name', 'districts.name as district_name', 'wards.name as ward_name')
            ->where('orders.code', $orderId)
            ->first();
        // Lấy các tham số từ request và kiểm tra tính hợp lệ
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_Amount = $request->input('vnp_Amount');

        // dd($vnp_ResponseCode);

        // Kiểm tra mã phản hồi
        if ($vnp_ResponseCode == '00') {
            // Thanh toán thành công
            // Cập nhật trạng thái đơn hàng trong cơ sở dữ liệu
            $order = Order::where('code', $vnp_TxnRef)->first();
            if ($order) {
                $order->payment_method = 'vnpay';
                $order->status = 1;
                $order->save();
            }
            return redirect()->route('homepage')->with('payment-success', 'Đã thanh toán đơn hàng thành công!');
        } else if ($vnp_ResponseCode == '11') {
            // Thanh toán thất bại do hết hạn giao dịch
            return redirect()->route('viewPaymentFail', ['orderId' => $order->code])->with('cancel-payment', 'HẾT THỜI GIAN GIAO DỊCH BẰNG VNPAY BANKING!');
        } else {
            // Thanh toán thất bại do người dùng hủy thanh toán <=> $vnp_ResponseCode == '24'
            return redirect()->route('viewPaymentFail', ['orderId' => $order->code])->with('cancel-payment', 'THANH TOÁN ONLINE BẰNG VNPAY BANKING KHÔNG THÀNH CÔNG!');
        }
    }

    public function showQRCode(Request $request)
    {
        $qrUrl = $request->query('qrUrl');
        $orderId = $request->query('orderId');
        return view('payment.banking-payment', ['qrUrl' => $qrUrl, 'orderId' => $orderId]);
    }
    public function updateOrderStatus($orderId)
    {
        // Find the order by its code
        $order = Order::where('code', $orderId)->first();
        if ($order) {
            $order->payment_method = 'banking_transfer';
            $order->status = 2;
            $order->save();
            return redirect()->route('homepage')->with('payment-check', 'KTMobile Shop sẽ liên hệ bạn để xác nhận đơn hàng!');
        } else {
            return redirect()->route('homepage')->with('error', 'Đặt hàng không thành công!');
        }
    }

    public function CashMoney(Request $request)
    {
        $order = Order::where('code', $request->input('code'))->first();
        if ($order) {
            $order->payment_method = 'cash_money';
            $order->status = 2;
            $order->save();
            return redirect()->route('homepage')->with('payment-check', 'KTMobile Shop sẽ liên hệ bạn để xác nhận đơn hàng!');
        } else {
            return redirect()->route('homepage')->with('error', 'Đặt hàng không thành công!');
        }
    }

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
    public function store(StorePaymentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
