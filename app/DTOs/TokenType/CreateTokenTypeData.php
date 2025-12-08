<?php

namespace App\DTOs\TokenType;

use App\Enums\TokenLocation;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Unique;

class CreateTokenTypeData extends Data
{
    public function __construct(
        #[Unique('token_types', 'name'), Max(30)]
        public string $name,

        #[Enum(TokenLocation::class)]
        public string $location,

        public string $param_name,

        #[Regex('/^(?:\{\}|[A-Za-z0-9_]+ \{\})$/')]
        public ?string $value_template = '{}',
        
        public ?bool $is_active = true,
    ) {}
}