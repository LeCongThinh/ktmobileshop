<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR code - Banking</title>
</head>
<style>
    .content-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
</style>
<body>
    <div class="content-center">
        <h1>Quét mã QR này để thanh toán đơn hàng</h1>
        <h1 id="countdown"></h1>
        <img src="{{ $qrUrl }}" alt="VietQR Code" id="qrCodeImage">
    </div>
    <script>
        var secondsLeft = 180;

        // Hiển thị thời gian đếm ngược ban đầu
        document.getElementById('countdown').textContent = 'Thời gian còn lại: ' + secondsLeft + ' giây';

        // Bắt đầu đếm ngược và tự động quay lại trang thanh toán sau khi hết thời gian
        var countdownInterval = setInterval(function() {
            secondsLeft--;
            document.getElementById('countdown').textContent = 'Thời gian còn lại: ' + secondsLeft + ' giây';

            if (secondsLeft <= 0) {
                clearInterval(countdownInterval); // Dừng đếm ngược
                window.location.href = '{{ route("updateOrderStatus", ["orderId" => $orderId]) }}';
            }
        }, 1000); // Cứ mỗi giây cập nhật thời gian đếm ngược
    </script>
</body>
</html>
