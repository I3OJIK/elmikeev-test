<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

abstract class BaseCommand extends Command
{
    /**
     * Обработка ошибок валидации для консольных команд
     * 
     * @param ValidationException $e
     * 
     * @return int
     */
    protected function handleValidationException(ValidationException $e): int
    {
        $this->error('Validation errors:');
        
        foreach ($e->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $this->line("  - {$field}: {$message}");
            }
        }
        
        return SELF::FAILURE;
    }
    
    /**
     * Обработка общих исключений
     * 
     * @param \Exception $e
     * 
     * @return int
     */
    protected function handleGenericException(\Exception $e): int
    {
        $this->error("Error: " . $e->getMessage());
        
        return SELF::FAILURE;
    }
}

