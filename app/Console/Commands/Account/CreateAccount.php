<?php

namespace App\Console\Commands\Account;

use App\Console\Commands\BaseCommand;
use App\DTOs\Account\CreateAccountData;
use App\Services\AccountService;
use Illuminate\Validation\ValidationException;

class CreateAccount extends BaseCommand
{
    protected $signature = 'app:account:create
        {company_id : ID компании}
        {name : Название аккаунта (максимум 50 символов)}';

    protected $description = 'Создать новый аккаунт для компании';

    public function handle(AccountService $service): int
    {
        try {
            // Создаем DTO и валидируем
            $data = CreateAccountData::validateAndCreate([
                'company_id' => $this->argument('company_id'),
                'name' => $this->argument('name'),
            ]);

            $company = $service->create($data);
           
            $this->info("Аккаунт создан с ID: {$company->id}"); 
            return Self::SUCCESS;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
