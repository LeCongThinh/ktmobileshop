@php
    use Carbon\Carbon;
@endphp
@extends('admin.app')
@section('title', 'Admin - Thông tin tài khoản')
@section('header-route')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Quản lý tài khoản</a></li>
    <li class="breadcrumb-item active" aria-current="page">Thông tin tài khoản</li>
@endsection
@section('account-active', 'active')
@section('content-pages')
    <h5 class="h4 mb-2 text-gray-800">Chi tiết tài khoản</h5>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">

                <div class="card-body">
                    <div class="avatar" style="display: block;  margin-left: auto;  margin-right: auto;text-align: center;">
                        <img src="{{ $acc->avatar }}" class="img-thumbnail" width="150">
                    </div><br>
                    <h4 style="text-align: center;"><b>{{ $acc->name }}</b></h4>
                    {{-- <p><b>Họ tên: </b> {{ $acc->name }}</p> --}}
                    <p><b>Email: </b> {{ $acc->email }}</p>
                    <p><b>Số điện thoại: </b>
                        @if ($acc->phone == null)
                            *Chưa cập nhật*
                        @endif
                        {{ $acc->phone }}
                    </p>
                    <p><b>Giới tính: </b>
                        @if ($acc->gender == 'male')
                            Nam
                        @elseif ($acc->gender == 'female')
                            Nữ
                        @elseif ($acc->gender == null)
                            *Chưa cập nhật*
                        @endif
                    </p>
                    <p><b>Ngày sinh: </b>
                        @if ($acc->birthday == null)
                            *Chưa cập nhật*
                        @else
                            {{ Carbon::parse($acc->birthday)->format('d/m/Y') }}
                        @endif
                    </p>
                    <p><b>Địa chỉ: </b>
                        {{-- {{ $acc->address . ', ' . $user->ward_name . ', ' . $user->district_name . ', tỉnh' . $user->province_name }}.</p> --}}
                        {{-- Nếu 1 trong các trường bị NULL thì sẽ không in ra --}}
                        @if (empty($acc->address) && empty($user->ward_name) && empty($user->district_name) && empty($user->province_name))
                            *Chưa cập nhật*
                        @else
                            @isset($acc->address)
                                {{ $acc->address }},
                            @endisset
                            @isset($user->ward_name)
                                {{ $user->ward_name }},
                            @endisset
                            @isset($user->district_name)
                                {{ $user->district_name }},
                            @endisset
                            @isset($user->province_name)
                                tỉnh {{ $user->province_name }}.
                            @endisset
                        @endif
                    </p>
                    <p><b>Chức vụ: </b>
                        @if ($acc->role == 1)
                            Admin
                        @elseif ($acc->role == 0)
                            Nhân viên
                        @endif
                    </p>
                    <a href="{{ route('accounts.edit', $acc) }}" class="btn btn-success btn-icon-split float-right">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Cập nhật tài khoản</span>
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
