<?php

namespace App\DTOs;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class AttachTokenTypeToServiceData extends Data
{
    public function __construct(
        #[Exists('api_services', 'id')]
        public int $api_service_id,

        #[Exists('token_types', 'id')]
        public int $token_type_id,
    ) {}
}