# Тестовое задание

## Реализация

 #### В проекте реализованы консольные команды:
- app:create-company - cоздание новой компании
- app:create-account - создание нового аккаунта
- app:create-account-token - создание нового токена аккаунта
- app:create-token-type - создание нового типа токена для API-авторизации
- app:create-api-service - создание новго API-сервиса
- app:attach-token-type - привязка типа токена к апи сервису
- app:sync-account - синхронизация данных определенного аккаунта с API-сервиса в БД
- app:sync-all-data - синхронизация всех данных (всех аккаунтов) из API-сервиса в БД

#### Создан планировщик, который синхронизирует данные 2 раза в день.

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