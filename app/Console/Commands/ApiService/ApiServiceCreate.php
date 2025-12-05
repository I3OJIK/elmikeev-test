<?php

namespace App\Console\Commands\ApiService;

use App\Console\Commands\BaseCommand;
use App\DTOs\ApiService\CreateApiServiceData;
use App\Services\ApiServiceService;
use Illuminate\Validation\ValidationException;

class ApiServiceCreate extends BaseCommand
{
    protected $signature = 'api-service:create
                            {name : API service name (max: 50 chars)}
                            {base_url : Base URL of service}
                            {--inactive : Create service as inactive}';

    protected $description = 'Create a new API service';

    public function handle(ApiServiceService $service): int
    {
        try {
            $data = CreateApiServiceData::validateAndCreate([
                'name'      => $this->argument('name'),
                'base_url'  => $this->argument('base_url'),
                'is_active' => !$this->option('inactive'),
            ]);

            $apiService = $service->create($data);

            $this->info("API Service created with ID: {$apiService->id} ");
            return 0;

        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
