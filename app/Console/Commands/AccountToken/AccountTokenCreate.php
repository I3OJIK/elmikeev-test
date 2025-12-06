<?php

namespace App\Console\Commands\AccountToken;

use App\Console\Commands\BaseCommand;
use App\DTOs\Account\CreateAccountData;
use App\DTOs\AccountToken\CreateAccountTokenData;
use App\Services\AccountService;
use App\Services\AccountTokenService;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class AccountTokenCreate extends BaseCommand
{
    protected $signature = 'account-token:create
                            {account_id}
                            {api_service_id}
                            {token_type_id}
                            {token_value}
                            {--encode64 : Encode token_value in Base64 for Basic auth}';

    protected $description = 'Create a new account-token';

    public function handle(AccountTokenService $service): int
    {
        try {
            $tokenValue = $this->argument('token_value');

            if ($this->option('encode64')) {
                $tokenValue = base64_encode($this->argument('token_value'));
            }

            // Создаем DTO и валидируем
            $data = CreateAccountTokenData::validateAndCreate([
                'account_id' => $this->argument('account_id'),
                'api_service_id' => $this->argument('api_service_id'),
                'token_type_id' => $this->argument('token_type_id'),
                'token_value' => $tokenValue,
            ]);
            $accountToken = $service->create($data);
           
            $this->info("Account token created with ID: {$accountToken->id}"); 
            return 0;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (InvalidArgumentException $e) {
            $this->error("error: {$e->getMessage()}");
            return 1;
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }

        
    }
}
