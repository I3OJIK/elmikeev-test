<?php

namespace App\Console\Commands\Company;

use App\Console\Commands\BaseCommand;
use App\DTOs\Company\CreateCompanyData;
use App\Services\CompanyService;
use Illuminate\Validation\ValidationException;

class CompanyCreate extends BaseCommand
{
    protected $signature = 'company:create
                            {name : Company name (max: 50 chars)}
                            {--inactive : Create company as inactive}';

    protected $description = 'Create a new company';

    public function handle(CompanyService $service): int
    {
        try {
            // Создаем DTO и валидируем
            $data = CreateCompanyData::validateAndCreate([
                'name' => $this->argument('name'),
                'is_active' => !$this->option('inactive') // инвертируем флаг
            ]);

            $company = $service->create($data);
           
            $this->info("Company created with ID: {$company->id}"); 
            return 0;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
