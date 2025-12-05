<?php

namespace App\Console\Commands\Account;

use App\Console\Commands\BaseCommand;
use App\DTOs\Account\CreateAccountData;
use App\Services\AccountService;
use Illuminate\Validation\ValidationException;

class AccountCreate extends BaseCommand
{
    protected $signature = 'account:create
                            {company_id : Company ID}
                            {name : Company name (max: 50 chars)}
                            {--inactive : Create company as inactive}';

    protected $description = 'Create a new account';

    public function handle(AccountService $service): int
    {
        try {
            // Создаем DTO и валидируем
            $data = CreateAccountData::validateAndCreate([
                'company_id' => $this->argument('company_id'),
                'name' => $this->argument('name'),
                'is_active' => !$this->option('inactive') // инвертируем флаг
            ]);

            $company = $service->create($data);
           
            $this->info("Account created with ID: {$company->id}"); 
            return 0;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
