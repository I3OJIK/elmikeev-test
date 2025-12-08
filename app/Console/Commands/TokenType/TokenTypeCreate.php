<?php

namespace App\Console\Commands\TokenType;

use App\Console\Commands\BaseCommand;
use App\DTOs\TokenType\CreateTokenTypeData;
use App\Services\TokenTypeService;
use Illuminate\Validation\ValidationException;

class TokenTypeCreate extends BaseCommand
{
    protected $signature = 'app:tokenType:create
        {name : Название типа токена (например: Bearer, Basic, API Key)}
        {location : Место размещения (header или query)}
        {param_name : Имя параметра в заголовке или query-строке}
        {--value_template= : Шаблон значения (например: "Bearer {}")}';

    protected $description = 'Создать новый тип токена для API-авторизации';

    public function handle(TokenTypeService $service): int
    {
        try {
            // Создаем DTO и валидируем
            $data = CreateTokenTypeData::validateAndCreate([
                'name' => $this->argument('name'),
                'location' => $this->argument('location'),
                'param_name' => $this->argument('param_name'),
                'value_template' => $this->option('value_template') ?? '{}',
            ]);

            $tokenType = $service->create($data);
           
            $this->info("Тип токена создан с ID: {$tokenType->id}"); 
            return SELF::SUCCESS;
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
