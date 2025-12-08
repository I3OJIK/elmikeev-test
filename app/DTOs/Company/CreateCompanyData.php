<?php

namespace App\DTOs\Company;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;

class CreateCompanyData extends Data
{
    public function __construct(
        #[Unique('companies', 'name'), Max(30)]
        public string $name,
    ) {}
}