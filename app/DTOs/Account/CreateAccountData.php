<?php

namespace App\DTOs\Account;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;

class CreateAccountData extends Data
{
    public function __construct(
        #[Exists('companies', 'id')]
        public int $company_id,
        
        #[Unique('accounts', 'name'), Max(30)]
        public string $name,
    ) {}
}