<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountToken;
use App\Models\ApiService;
use App\Models\Company;
use App\Models\TokenType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'company',
        ]);

        Account::create([
            'company_id' => 1,
            'name' => 'account 1',
        ]);

        TokenType::create([
            'name' => 'Key',
            'location' => 'query',
            'param_name' => 'key',
            'value_template' => '{}',
        ]);
        
        ApiService::create([
            'name' => 'Service 1',
            'base_url' => 'http://109.73.206.144:6969/api/',
        ]);
        
        AccountToken::create([
            'account_id' => 1,
            'api_service_id' => 1,
            'token_type_id' => 1,
            'token_value' => 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie',
        ]);
        
        DB::table('api_service_token_types')->insert(
            [
                'api_service_id' => 1,
                'token_type_id' => 1,
            ]
        );
    }
}
