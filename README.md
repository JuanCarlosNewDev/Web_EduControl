# EduControl

Sistema web de gestión académica y control de asistencia desarrollado con Laravel 13, Blade y MySQL/MariaDB (PHP 8.4.1 o superior).

## Funcionalidades

- Inicio de sesión con dos roles: administrador y docente.
- Panel de resumen con estudiantes, cursos, asistencia del día y porcentaje mensual.
- CRUD de estudiantes, cursos y asistencias con validación.
- Búsqueda de estudiantes/cursos y filtros de asistencias por curso, estado, fecha y estudiante.
- Asignación de cursos a docentes y acceso limitado a las asistencias de sus propios cursos.
- Protección contra registros duplicados de asistencia para el mismo estudiante, curso y fecha.
- Gestión de planes de estudio asociados a estudiantes, cursos y sílabos.
- Carga de sílabos PDF por curso por parte del docente, con aprobación administrativa; los sílabos generales por plan siguen siendo administrados por el administrador.
- Horarios semanales por curso, con detección de cruces para un mismo docente.
- Registro de múltiples evaluaciones por curso, notas de 0 a 20 y promedio automático por estudiante.

## Instalación

1. Instala PHP 8.4.1+, Composer, Node.js y MySQL/MariaDB. Con XAMPP, inicia MySQL desde el panel de control.
2. Crea la base de datos `eduControl` en MySQL y configura `.env` con `DB_DATABASE=eduControl`, `DB_USERNAME` y `DB_PASSWORD`.
3. Ejecuta:

```powershell
composer install
php artisan key:generate
npm install
php artisan migrate --seed
npm run build
php artisan serve
```

Abre `http://127.0.0.1:8000`.

## Cuentas de demostración

El seeder crea estas cuentas locales. Cambia las contraseñas antes de publicar el sistema:

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | `admin@educontrol.test` | `password` |
| Docente | `docente@educontrol.test` | `password` |

El administrador crea primero los **Planes de estudio**, luego asigna cada estudiante y curso a su plan. Desde **Horarios** agrega las sesiones semanales y desde **Sílabos** carga y publica PDFs generales por plan, además de revisar los sílabos enviados por los docentes para sus cursos. El docente puede subir el PDF del curso que tiene asignado; queda pendiente hasta que administración lo apruebe. También consulta sílabos publicados, toma asistencia y registra notas únicamente en sus cursos asignados. La primera migración elimina permanentemente las tablas, adjuntos, cuentas y sesiones heredadas de Mesa de Partes en la base configurada; respalda cualquier dato que quieras conservar y confirma que `.env` apunta a la base correcta antes de ejecutar migraciones.

## Verificación

```powershell
php artisan test
npm run build
```
