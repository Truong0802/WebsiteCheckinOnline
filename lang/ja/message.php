<?php

// Thông điệp dùng chung cho API (ApiExceptionHandler, AuthenticationService).
return [
    'logout_success' => 'ログアウトしました。',
    'errors' => [
        'bad_request' => '不正なリクエストです。',
        'unauthorized' => '認証されていません。',
        'forbidden' => 'この操作を行う権限がありません。',
        'page_not_found' => 'リソースが見つかりません。',
        'model_not_found' => 'データが存在しません。',
        'method_not_allowed' => '許可されていないHTTPメソッドです。',
        'request_invalid' => '送信されたデータが不正です。',
        'server_error' => 'システムエラーが発生しました。しばらくしてから再度お試しください。',
    ],
];
