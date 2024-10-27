<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAccountRequest;
use App\Mail\VerifyAccountMail;
use App\Models\Members;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view("register");
    }
    public function registerAccount(RegisterAccountRequest $request)
    {
        try
        {
            $characterSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $acc = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 2,
                'status' => 0,
            ]);
            // Save the account
            $acc->save();
            if ($acc->role == 2) {
                $member = Members::create([
                    'code' => substr(str_shuffle($characterSet), 0, 10), // Tạo mã ngẫu nhiên
                    'name' => $acc->name, // Sử dụng tên từ tài khoản
                    'start_day' => Carbon::now(), // Ngày bắt đầu là ngày hiện tại
                    'end_day' => Carbon::now()->addYear(), // Ngày kết thúc là một năm sau
                    'score' => 0, // Điểm ban đầu
                    'user_id' => $acc->id, // ID của người dùng
                ]);
                $member->save();
            }

            // Tạo token xác thực
            $token = Str::random(60);
            DB::table('user_verifications')->insert([
                'user_id' => $acc->id,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
            //Gửi mail với template là "mail-verify-account.blade.php"
            // Mail::send("mail-verify-account", ['token' => $token], function ($message) use ($request) {
            //     $message->to($request->email);
            //     $message->subject("Kích hoạt tài khoản - KTMobile Shop");
            // });

            Mail::to($request->email)->send(new VerifyAccountMail($token));

            
            // Thành cônng -> Redirect sang trang login
            return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.');
        } catch (\Exception $e) {
            // Thất bại -> Redirect sang trang login
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

    public function verifyAccount($token)
    {
        $verify = DB::table('user_verifications')->where('token', $token)->first();

        if ($verify) {
            $user = User::find($verify->user_id);
            $user->status = 1; // Kích hoạt tài khoản
            $user->save();

            // Xóa token xác thực sau khi đã xác thực
            DB::table('user_verifications')->where('token', $token)->delete();

            return redirect()->route('login')->with('success', 'Tài khoản của bạn đã được xác thực thành công!');
        }

        return redirect()->route('login')->with('error', 'Token xác thực không hợp lệ hoặc đã hết hạn.');
    }

}
