<?php

namespace App\DTOs\AccountToken;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class CreateAccountTokenData extends Data
{
    public function __construct(
        #[Exists('accounts', 'id')]
        public int $account_id,

        #[Exists('api_services', 'id')]
        public int $api_service_id,

        #[Exists('token_types', 'id')]
        public int $token_type_id,
        
        public string $token_value,
        
    ) {}
}