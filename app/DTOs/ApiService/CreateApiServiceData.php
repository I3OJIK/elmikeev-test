<?php

namespace App\DTOs\ApiService;

use App\Enums\TokenLocation;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\Validation\Url;

class CreateApiServiceData extends Data
{
    public function __construct(
        #[Unique('api_services', 'name'), Max(50)]
        public string $name,

        #[Url(['http', 'https'])]
        public string $base_url,
    ) {}
}