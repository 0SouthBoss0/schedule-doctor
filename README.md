
# Медицинская система записи на прием

## Описание проекта
RESTful API для управления записью на приемы с возможностью:
- Создания слотов для записи
- Получить информацию о свободных слотах
- Запись на прием

## Требования
- PHP 8.4.1
- Composer 2.8.3
- SQLite 2.6.0
- Laravel 12.1.1
  
## Установка
```bash
git clone https://github.com/nadezhkinaa/schedule-doctor.git
cd schedule-doctor
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

# API Endpoints
## 1. Создание слотов
```POST /api/v1/add-slots```

Тело:

```json
{
    "doctor_id": 3,
    "start_time": "2023-11-22 19:00:00",
    "end_time": "2023-11-22 19:20:00"
}
```
Ответ:
```json
{
    "errors": [],
    "data": {
        "doctor_id": 3,
        "start_time": "2024-11-22 19:00:00",
        "end_time": "2024-11-22 19:20:00",
        "is_available": true,
        "updated_at": "2025-04-08T18:13:45.000000Z",
        "created_at": "2025-04-08T18:13:45.000000Z",
        "id": 16
    }
}
```
## 2. Получить информацию о пациенте по ID

```GET /api/v1/free-slots/{id}```

Пример:

```GET /api/v1/free-slots/3```

Ответ:
```json
{
    "errors": [],
    "data": [
        {
            "id": 15,
            "doctor_id": 3,
            "start_time": "2023-11-22 19:00:00",
            "end_time": "2023-11-22 19:20:00",
            "is_available": 1,
            "created_at": "2025-04-07T16:19:30.000000Z",
            "updated_at": "2025-04-07T16:19:30.000000Z"
        },
        {
            "id": 16,
            "doctor_id": 3,
            "start_time": "2024-11-22 19:00:00",
            "end_time": "2024-11-22 19:20:00",
            "is_available": 1,
            "created_at": "2025-04-08T18:13:45.000000Z",
            "updated_at": "2025-04-08T18:13:45.000000Z"
        }
    ]
}
```
## 3. Запись на прием

```POST /api/v1/book-appointments```

Тело:

```json
{
    "schedule_id":15,
    "patient_id": "2"
}
```

Ответ:

```json
{
    "message": "Appointment booked successfully",
    "data": {
        "schedule_id": 15,
        "patient_id": "2",
        "updated_at": "2025-04-08T18:18:35.000000Z",
        "created_at": "2025-04-08T18:18:35.000000Z",
        "id": 8
    }
}
```
