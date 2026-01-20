# GYM API

API REST para la gestión de un gimnasio. Los usuarios pueden consultar clases, ver sesiones disponibles y hacer reservas. Los administradores gestionan todo el sistema.

## Características

- **Gestión de clases** - Yoga, spinning, pilates, etc.
- **Programación de sesiones** - Fecha, hora y capacidad
- **Sistema de reservas** - Control automático de plazas disponibles
- **Estadísticas de usuario** - Reservas confirmadas, canceladas y asistencias
- **Control de acceso por roles** - Administrador y usuario

## Stack Tecnológico

- **Backend:** PHP 8.2, Laravel 11
- **Base de datos:** MySQL
- **Autenticación:** Laravel Passport (OAuth2)
- **Testing:** PHPUnit (50 tests, 126 assertions)

## Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/mikelji97/GYM-API.git
cd GYM-API
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con los datos de tu base de datos:
```env
DB_DATABASE=gym_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Configurar base de datos
```bash
php artisan migrate
```

### 5. Configurar Passport
```bash
php artisan passport:keys
php artisan passport:client --personal
```

### 6. Arrancar servidor
```bash
php artisan serve
```

API disponible en `http://127.0.0.1:8000/api`

## Autenticación

Todas las rutas protegidas requieren el header:
```
Authorization: Bearer {tu_token}
```

El token se obtiene al hacer login mediante `POST /api/login`.

## Roles y Permisos

| Rol | Permisos |
|-----|----------|
| **user** | Ver clases y sesiones<br>Crear y cancelar sus propias reservas<br>Ver sus estadísticas |
| **admin** | Todos los permisos de usuario<br>Crear, editar y eliminar clases y sesiones<br>Ver todas las reservas<br>Cancelar cualquier reserva |

## Endpoints

### Autenticación

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/register` | Crear cuenta |
| POST | `/api/login` | Obtener token |
| POST | `/api/logout` | Cerrar sesión |

### Clases

| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | `/api/gym-classes` | Listar todas | Todos |
| GET | `/api/gym-classes/{id}` | Ver una clase | Todos |
| POST | `/api/gym-classes` | Crear clase | Admin |
| PUT | `/api/gym-classes/{id}` | Editar clase | Admin |
| DELETE | `/api/gym-classes/{id}` | Eliminar clase | Admin |

### Sesiones

| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | `/api/sessions` | Listar todas | Todos |
| GET | `/api/sessions/available` | Solo con plazas | Todos |
| GET | `/api/sessions/{id}` | Ver una sesión | Todos |
| POST | `/api/sessions` | Crear sesión | Admin |
| PUT | `/api/sessions/{id}` | Editar sesión | Admin |
| DELETE | `/api/sessions/{id}` | Eliminar sesión | Admin |

### Usuarios

| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | `/api/users` | Listar usuarios | Admin |
| GET | `/api/users/{id}` | Ver perfil | Propio o Admin |
| PUT | `/api/users/{id}` | Editar perfil | Propio o Admin |
| DELETE | `/api/users/{id}` | Eliminar usuario | Admin |
| GET | `/api/users/{id}/stats` | Estadísticas | Propio o Admin |

### Reservas

| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | `/api/bookings` | Listar reservas | User (propias) / Admin (todas) |
| GET | `/api/bookings/my-bookings` | Mis reservas | User |
| POST | `/api/bookings` | Crear reserva | User |
| DELETE | `/api/bookings/{id}` | Cancelar reserva | User (propia) / Admin (cualquiera) |

## Validaciones

- No se puede reservar una sesión que está llena
- No se puede reservar dos veces la misma sesión
- Al cancelar una reserva se libera la plaza automáticamente

## Testing
```bash
php artisan test
```

**Resultado:** 50 tests, 126 assertions

## Estructura del Proyecto
```
app/
├── Http/
│   └── Controllers/
│       ├── AuthController.php
│       ├── BookingController.php
│       ├── GymClassController.php
│       ├── SessionController.php
│       └── UserController.php
└── Models/
    ├── Booking.php
    ├── GymClass.php
    ├── Session.php
    └── User.php

database/
├── factories/
└── migrations/

tests/
└── Feature/
    ├── AuthTest.php
    ├── BookingTest.php
    ├── GymClassTest.php
    ├── SessionTest.php
    └── UserTest.php
```

## Licencia

Este proyecto es de código abierto.

## Autor

**Mikel**
- GitHub: [@mikelji97](https://github.com/mikelji97)
