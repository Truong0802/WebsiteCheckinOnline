<?php

// Thông điệp dùng chung cho API (ApiExceptionHandler, AuthenticationService).
return [
    'logout_success' => 'Logged out successfully.',
    'supplier' => [
        'deleted' => 'Supplier deleted successfully.',
        'in_use' => 'Cannot delete a supplier that is referenced by other data.',
    ],
    'errors' => [
        'bad_request' => 'Bad request.',
        'unauthorized' => 'Unauthenticated.',
        'forbidden' => 'You do not have permission to perform this action.',
        'page_not_found' => 'The requested resource could not be found.',
        'model_not_found' => 'The requested data does not exist.',
        'method_not_allowed' => 'The HTTP method is not allowed.',
        'request_invalid' => 'The submitted data is invalid.',
        'server_error' => 'An unexpected error occurred. Please try again later.',
    ],
];
