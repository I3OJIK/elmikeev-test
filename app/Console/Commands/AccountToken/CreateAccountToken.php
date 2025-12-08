<?php

namespace App\Console\Commands\AccountToken;

use App\Console\Commands\BaseCommand;
use App\DTOs\AccountToken\CreateAccountTokenData;
use App\Services\AccountTokenService;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class CreateAccountToken extends BaseCommand
{
    protected $signature = 'app:accountToken:create
        {account_id : ID аккаунта компании}
        {api_service_id : ID API-сервиса}
        {token_type_id : ID типа токена}
        {token_value : Значение токена (API ключ, пароль и т.д.)}
        {--encode64 : Закодировать token_value в Base64 (для Basic авторизации)}';

    protected $description = 'Создать новый токен доступа для аккаунта';

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
           
            $this->info("Токен аккаунта создан с ID: {$accountToken->id}"); 
            return Self::SUCCESS;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (InvalidArgumentException $e) {
            $this->error("error: {$e->getMessage()}");
            return Self::FAILURE;
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }

        
    }
}
