<?php

namespace App\Console\Commands\ApiService;

use App\Console\Commands\BaseCommand;
use App\DTOs\ApiService\CreateApiServiceData;
use App\Services\ApiServiceService;
use Illuminate\Validation\ValidationException;

class CreateApiService extends BaseCommand
{
    protected $signature = 'app:create-api-service
        {name : Название API-сервиса (максимум 50 символов)}
        {base_url : Базовый URL API-сервиса}';

    protected $description = 'Создать новый API-сервис';

    public function handle(ApiServiceService $service): int
    {
        try {
            $data = CreateApiServiceData::validateAndCreate([
                'name'      => $this->argument('name'),
                'base_url'  => $this->argument('base_url'),
            ]);

            $apiService = $service->create($data);

            $this->info("Апи сервис создан с ID: {$apiService->id} ");
            return Self::SUCCESS;

        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
