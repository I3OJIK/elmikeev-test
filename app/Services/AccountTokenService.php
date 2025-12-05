<?php

namespace App\Services;

use App\DTOs\AccountToken\CreateAccountTokenData;
use App\Models\AccountToken;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AccountTokenService
{

    public function create(CreateAccountTokenData $data): AccountToken
    {
        $exists = DB::table('api_service_token_types')
        ->where('api_service_id', $data->api_service_id)
        ->where('token_type_id', $data->token_type_id)
        ->exists();

        if (!$exists) {
            throw new InvalidArgumentException(
                "This token type is not allowed for API service {$data->api_service_id}"
            );
        }

        return AccountToken::create([
            $data->toArray()
        ]);
    }
}