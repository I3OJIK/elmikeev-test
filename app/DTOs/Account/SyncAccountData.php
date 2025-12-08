<?php

namespace App\DTOs\Account;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class SyncAccountData extends Data
{
    public function __construct(
        #[Exists('accounts', 'id')]
        public int $account_id,
    ) {}
}