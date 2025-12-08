<?php

namespace App\Console\Commands;

use App\DTOs\Account\SyncAccountData;
use App\Services\SyncService;
use Illuminate\Validation\ValidationException;

class SyncAccount extends BaseCommand
{
    protected $signature = 'app:sync-account {account_id}';

    protected $description = 'Синхронизация определенного аккаунта';


    public function handle(SyncService $syncService)
    {
        try {
            $data = SyncAccountData::validateAndCreate([
                'account_id' => $this->argument('account_id'),
            ]);
            $this->info("Начало синхронизации аккаунта {$data->account_id}");
            $syncService->setOutput($this->output);

            $syncService->syncAccount($data->account_id);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
