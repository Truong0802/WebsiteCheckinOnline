<?php

// Thông điệp dùng chung cho API (ApiExceptionHandler, AuthenticationService).
return [
    'logout_success' => 'Đăng xuất thành công.',
    'supplier' => [
        'deleted' => 'Xóa nhà cung cấp thành công.',
        'in_use' => 'Không thể xóa nhà cung cấp đang được sử dụng ở dữ liệu khác.',
    ],
    'errors' => [
        'bad_request' => 'Yêu cầu không hợp lệ.',
        'unauthorized' => 'Chưa đăng nhập hoặc phiên đăng nhập đã hết hạn.',
        'forbidden' => 'Bạn không có quyền thực hiện thao tác này.',
        'page_not_found' => 'Không tìm thấy tài nguyên yêu cầu.',
        'model_not_found' => 'Dữ liệu không tồn tại.',
        'method_not_allowed' => 'Phương thức HTTP không được hỗ trợ.',
        'request_invalid' => 'Dữ liệu gửi lên không hợp lệ.',
        'server_error' => 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.',
    ],
];
