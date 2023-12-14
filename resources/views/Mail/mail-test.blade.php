<?php
use Carbon\Carbon;
?>
<div>
    <h2>Xin chào {{$username}}</h2>
    <br>
    <p>
        Mật khẩu của bạn đang yêu cầu thay đổi!
        <?php
        $data = [
                   'user' => $username,
                   'Time' => Carbon::now(),
                ];
                $encryptedData = encrypt($data);
        ?>
        vui lòng  <a href="{{ route('ConfirmToChange', ['data' => $encryptedData]) }}" >bấm vào đây để thay đổi</a>
    </p>
</div>
