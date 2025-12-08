<?php

namespace App\Console\Commands;

use App\DTOs\AttachTokenTypeToServiceData;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttachTokenTypeToService extends BaseCommand
{
    protected $signature = 'app:attach-token-type 
        {api_service_id : ID API-сервиса}
        {token_type_id : ID типа токена}';

    protected $description = 'Привязать тип токена к API-сервису';


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
           
            $this->info("Тип токена прикреплен"); 
            return SELF::SUCCESS;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
