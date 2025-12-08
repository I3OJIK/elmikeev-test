# Тестовое задание

## Реализация

 #### В проекте реализованы консольные команды:
- app:company:create - cоздание новой компании
- app:account:create - создание нового аккаунта
- app:accountToken:create - создание нового токена аккаунта
- app:tokenType:create - создание нового типа токена для API-авторизации
- app:apiService:create- - создание новго API-сервиса
- app:attach-token-type - привязка типа токена к апи сервису
- app:sync:account - синхронизация данных определенного аккаунта с API-сервиса в БД
- app:sync:all-data - синхронизация всех данных (всех аккаунтов) из API-сервиса в БД

#### Создан планировщик, который синхронизирует данные 2 раза в день:
- для работы планировщика нужно настроить cron

## Setup

### Клонирование проекта:
```bash
git clone https://github.com/I3OJIK/elmikeev-test.git elmikeev  
cd elmikeev
```
### Разворачивание проекта:

Если порт 3308 свободен запускаем команду:
```bash
make setup
```
Если нужно изменить порт:
```bash
# Добавляем в .env.example DB_EXTERNAL_PORT и после этого 
make setup
```
### Настройка cron:

Добавьте в crontab (`crontab -e`):
```bash
* * * * * cd /absolute/path/to/project && docker compose exec php php artisan schedule:run >> /dev/null 2>&1
```