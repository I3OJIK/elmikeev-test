<?php

namespace App\Console\Commands\TokenType;

use App\Console\Commands\BaseCommand;
use App\DTOs\Account\CreateAccountData;
use App\DTOs\AccountToken\CreateAccountTokenData;
use App\DTOs\TokenType\CreateTokenTypeData;
use App\Services\AccountService;
use App\Services\AccountTokenService;
use App\Services\TokenTypeService;
use Illuminate\Validation\ValidationException;

class TokenTypeCreate extends BaseCommand
{
    protected $signature = 'token-type:create
                            {name : Token type name, e.g. Bearer, Basic, API Key}
                            {location : header or query}
                            {param_name : Name of header or query parameter}
                            {--value_template= : Template, e.g. "Bearer {}"}';

    protected $description = 'Create a new token type';

    public function handle(TokenTypeService $service): int
    {
        try {
            // Создаем DTO и валидируем
            $data = CreateTokenTypeData::validateAndCreate([
                'name' => $this->argument('name'),
                'location' => $this->argument('location'),
                'param_name' => $this->argument('param_name'),
                'value_template' => $this->option('value_template') ?? '{}',
            ]);

            $tokenType = $service->create($data);
           
            $this->info("token type created with ID: {$tokenType->id}"); 
            return 0;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
