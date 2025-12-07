<?php

namespace App\Console\Commands;

use App\DTOs\AttachTokenTypeToServiceData;
use App\Services\Api\ApiClientService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttachTokenTypeToService extends BaseCommand
{
    protected $signature = 'data:attach-token-type 
                            {api_service_id : ID API service}
                            {token_type_id : ID token}';

    protected $description = 'Attach Token Type To Service';


    public function handle()
    {   
        try {
            // Создаем DTO и валидируем
            $data = AttachTokenTypeToServiceData::validateAndCreate([
                'api_service_id' => $this->argument('api_service_id'),
                'token_type_id' => $this->argument('token_type_id') 
            ]);

            DB::table('api_service_token_types')->updateOrInsert(
                $data->toArray()
            );
           
            $this->info("Token type attached"); 
            return 0;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
