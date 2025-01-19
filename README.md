# Proyecto daily-trends-api

Este es un proyecto con el que scrapear las últimas 5 noticias de los portales de noticias y servirlas en una API

## Requisitos

Antes de comenzar, asegúrate de tener instaladas las siguientes herramientas:

- **PHP 8.2+** (Se recomienda la versión más reciente de PHP).
- **Composer**
- **MongoDB**
- **Git**

## Instalación

Sigue estos pasos para inicializar el proyecto en tu máquina local:

### 1. Clonar el repositorio

Clona el repositorio usando Git. Abre tu terminal y ejecuta:

```bash
git clone https://github.com/f3rran/daily-trends-api.git
```

### 2. Instalar las dependencias
Entra al directorio del proyecto e instala las librerías de PHP:

```bash
cd daily-trends-api
composer install
```

### 3. Copiar el .env

```bash
cp .env.example .env
```

### 4. Configurar las credenciales de la BBDD
En este caso mongodb:

```bash
MONGODB_URI="mongodb://localhost:27017"
MONGODB_DATABASE="daily-trends-api"
```



### 5. Generar la key de la aplicación
Laravel requiere una key para trabajar en base de datos y todas las tareas de hashing:

```bash
php artisan key:generate
```

### 6. Migrar la base de datos
Ejecuta las migraciones para inicializar la BBDD:

```bash
php artisan migrate
```

### 7. Ejecutar MongoDB en modo réplica

Para que funcionen los tests, que emplean transacciones es necesario ejecutar MongoDB en modo réplica: https://dev.to/sarwarasik/mongodb-transactions-error-transaction-numbers-are-only-allowed-on-a-replica-set-member-or-mongos-4083

### Swagger

Se encuentra disponible en http://localhost:8000/api/documentation

### Arrancar el servidor de desarrollo
Se puede inicializar mediante el comando:

```bash
php artisan serve
```

Esto iniciará el servidor en http://localhost:8000 por defecto. Se le puede pasar un puerto específico.

## Rutas de la API
A continuación se describen las rutas básicas de la API de Feeds:

- GET /api/feeds: Obtener todos los artículos.
- POST /api/feeds: Crear un nuevo artículos.
- GET /api/feeds/{id}: Obtener un artículos específico por ID.
- PUT /api/feeds/{id}: Actualizar un artículos existente.
- DELETE /api/feeds/{id}: Eliminar un artículos por ID.

Notas adicionales
Si tienes alguna pregunta o necesitas ayuda, por favor abre un Issue en el repositorio o contacta al mantenedor.

## Ejecutar scrapers

La aplicación cuenta con un comando automatizado para realizar los scrapings:

```bash
php artisan app:scraping-command
```

## Arquitectura

Se cuenta con un controlador principal: FeedController que contiene toda la lógica de los endpoints creados. Estos siguen la arquitectura básica de un CRUD de Laravel para generar un 'ApiResource'. Anteriormente ya se han listado dichos endpoints. Dentro de dicho controlador se ha aplicado el patrón repositorio para extraer la lógica de Eloquent a un repositorio donde es mucho más mantenible y substituible en caso de ser necesario.

Por otra parte se cuenta con command para lanzar los scrapers que ya se ha listado anteriormente. Este command lanza los servicios encargados de scrapear las dos páginas web requeridas. Como se ha comentado, la logica de cada scrapeo se ha abstraído a un servicio con el que poder reutilizar y mantener de mejor forma dicha lógica. Para su lanzamiento se ha empleado el command de Laravel, pero se podrían llamar simplemente en un endpoint y convertirlo en una especie de webhook. En los scrapers se ha aprovechado el FeedRepository nombrado anteriormente para almacenar los artículos.

Para implementar MongoDB se ha usado la implementación oficial de Laravel. Eloquent es compatible con dicha base de datos. Hay algunas consideraciones a tener en cuenta que se han comentado antes como el tema de las transacciones para poder lanzar los tests de forma correcta.

En `/public/images/arquitectura.png` se puede ver un diagrama de la arquitectura de la API con MongoDB.
