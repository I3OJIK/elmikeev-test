<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Services\Api\ApiClientService;

class SyncService
{
    public function __construct(
        private ApiClientService $apiClient
    ) {}

    /**
     * Синхронизирует данные из определенной сущности api в бд
     * 
     * @param EntityType $entityType тип сущности
     * @param array $params параметры запроса
     * 
     * @return int
     */
    public function syncEntity(EntityType $entityType, array $params = []): int
    {
        $totalProcessed = 0;

        $this->apiClient->fetchPaginatedData(
            $entityType->endpoint(),
            $this->getParamsForType($entityType, $params),
            function($batchData) use ($entityType, &$totalProcessed) {
                $entityType->modelClass()::insert($batchData);
                $totalProcessed += count($batchData);
            }
        );

        return $totalProcessed;
    }

    /**
     * Синхронизирует все данные из api в бд
     * 
     * @return array
     */
    public function syncAll(): array
    {
        $results = [];
        
        foreach (EntityType::cases() as $entityType) {
            $results[$entityType->value] = $this->syncEntity($entityType, $this->getParamsForType($entityType));
        }

        return $results;
    }

    /**
     * Формирует параметры запроса для указанного типа данных
     * 
     * @param EntityType $entityType
     * @param array $params
     * 
     * @return array
     */
    private function getParamsForType(EntityType $entityType, array $params = []): array
    {
        $baseParams = [];
        
        // Для складов только текущий день
        if ($entityType === EntityType::STOCKS) {
            return array_merge($baseParams, [
                'dateFrom' => now()->format('Y-m-d')
            ]);
        }

        // Для остальных - переданные параметры или по умолчанию
        return array_merge($baseParams, [
            'dateFrom' => $params['dateFrom'] ?? '0001-01-01',
            'dateTo' => $params['dateTo'] ?? now()->format('Y-m-d'),
        ]);
    }
}