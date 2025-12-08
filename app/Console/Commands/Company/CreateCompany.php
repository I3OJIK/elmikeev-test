<?php

namespace App\Console\Commands\Company;

use App\Console\Commands\BaseCommand;
use App\DTOs\Company\CreateCompanyData;
use App\Services\CompanyService;
use Illuminate\Validation\ValidationException;

class CreateCompany extends BaseCommand
{
    protected $signature = 'company:create
        {name : Название компании (максимум 50 символов)}';

    protected $description = 'Создать новую компанию в системе';

    public function handle(CompanyService $service): int
    {
        try {
            // Создаем DTO и валидируем
            $data = CreateCompanyData::validateAndCreate([
                'name' => $this->argument('name'),
            ]);

            $company = $service->create($data);
           
            $this->info("Компания создана с ID: {$company->id}"); 
            return Self::SUCCESS;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
