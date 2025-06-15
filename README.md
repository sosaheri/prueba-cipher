# Prueba técnica para ciph3r - Heriberto Sosa

API RESTful construida con Laravel 12 para la gestión de productos y divisas, diseñada con foco en la seguridad, rendimiento y escalabilidad.

---

## 📋 Tabla de Contenidos

1.  [Características Implementadas](#1-características-implementadas)
2.  [Requisitos del Sistema](#2-requisitos-del-sistema)
3.  [Instalación](#3-instalación)
4.  [Configuración](#4-configuración)
5.  [Ejecución del Proyecto](#5-ejecución-del-proyecto)
6.  [Endpoints de la API](#6-endpoints-de-la-api)
7.  [Autenticación (Sanctum)](#7-autenticación-sanctum)
8.  [Documentación de la API](#8-documentacion-de-la-api)

---

## 1. Características Implementadas

### Decisiones de Diseño y Arquitectura y Observaciones

**Arquitectura Monolito Modular:** El proyecto sigue una arquitectura monolito modular, donde las funcionalidades están divididas en módulos lógicos (app/Modules). Esto mejora la organización y mantenibilidad del código, permitiendo una clara separación de responsabilidades y facilitando el escalado de funciones específicas.

**Capa de Servicios:** Se implementó una capa de Services (app/Services o dentro de cada módulo) para contener la lógica de negocio. Esto desvincula los controladores de la lógica de la base de datos y otras operaciones, haciendo los controladores más "delgados" y enfocados en manejar las solicitudes HTTP.

**Rate Limiting Global:** La implementación de un Rate Limiter global para las rutas API protege contra el uso excesivo y asegura la disponibilidad y seguridad del servicio.

**Caching Estratégico:** El caching se aplicó a endpoints de lectura frecuentes con datos estables para maximizar el rendimiento y reducir la carga de la base de datos, sin comprometer la frescura de los datos críticos. La clave de caché dinámica asegura que las respuestas cacheadas sean específicas a cada consulta.

**Observación sobre Registro de Usuarios:** Se omitió la creación de un endpoint específico para el registro de usuarios (/api/auth/register) para enfocar el tiempo de la prueba en los requisitos principales de gestión de productos y las características adicionales solicitadas (Rate Limiting, Caching, Scaffolding). Se reconoce que un endpoint de registro sería necesario antes de la puesta en producción.

### Bases CRUD de la prueba

* **CRUD de Productos:**
    * **Propósito:** La API debe permitir crear, leer, actualizar y eliminar productos.
    * **Implementación:** Cada producto debe tener un nombre, descripción, precio, costo de impuestos y costo de fabricación. La API devuelve los datos en formato JSON y utiliza Eloquent para interactuar con la base de datos

* **Gestión de Precios en Múltiples Divisas:**
    * **Propósito:** La API debe permitir registrar precios de los productos en diferentes divisas.
    * **Implementación:** Se maneja a través de un modelo ProductPrice que relaciona productos con divisas y su precio específico.

### Adicionales

* **Token Auth:**
    * **Propósito:** Protección de acceso a las rutas.
    * **Implementación:** Se implemento Sactum y proteccion de rutas para acceder o crear datos.
* **Rate Limiting:**
    * **Propósito:** Protección contra ataques de fuerza bruta y DDoS, mejora la **seguridad**.
    * **Implementación:** Configurado en `bootstrap/app.php` con un límite de peticiones por minuto por usuario/IP.
* **Caching:**
    * **Propósito:** Mejora significativa del **rendimiento** para datos poco cambiantes (ej. listas de productos, divisas).
    * **Implementación:** Implementado en `ProductService` y `CurrencyService` con invalidación de caché al crear/actualizar/eliminar recursos. Se utiliza clave de caché dinámica para listas filtradas/paginadas.
* **Comando Artisan para Scaffolding de Módulos (`make:module`):**
    * **Propósito:** Demuestra **organización** y **escalabilidad** del proyecto al automatizar la creación de la estructura de un nuevo módulo.
    * **Implementación:** El comando crea la estructura de carpetas (`Controllers`, `Models`, `Services`, `Requests`, `routes`, etc.) y un archivo `api.php` básico. Las rutas se registran automáticamente vía `App\Providers\ModuleServiceProvider`.
* **Estructura Modular (app/Modules):**
    * **Propósito:** Mejora la **organización** y **mantenibilidad** de la base de código, facilitando el desarrollo y la escalabilidad de nuevas funcionalidades.
    * **Implementación:** Las funcionalidades están divididas en módulos lógicos (ej. `Currencies`, `Products`), cada uno con sus propias capas (Controllers, Services, Models, etc.).

---

## 2. Requisitos del Sistema

Asegúrate de tener instalado lo siguiente en tu máquina de desarrollo:

* PHP >= 8.2
* Composer
* Una base de datos MySQL 8+

---

## 3. Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/sosaheri/prueba-cipher.git
    cd prueba-cipher
    ```

2.  **Instalar dependencias de Composer:**
    ```bash
    composer install
    ```

3.  **Configurar el archivo de entorno (`.env`):**
    * Copia el archivo de ejemplo:
        ```bash
        cp .env.example .env
        ```
    * Genera una clave de aplicación:
        ```bash
        php artisan key:generate
        ```
    * Abre el archivo `.env` y configura tus credenciales de base de datos y otras variables de entorno (ej. `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `CACHE_DRIVER=file` o `redis`).

4.  **Ejecutar migraciones:**
    ```bash
    php artisan migrate
    ```
    *Si necesitas refrescar la base de datos (eliminar y recrear tablas):*
    ```bash
    php artisan migrate:fresh
    ```

---

## 4. Configuración

* **Base de Datos:** Configura las credenciales en tu `.env`.
* **Caché:** Asegúrate que `CACHE_DRIVER` esté configurado en `.env` (ej. `file` para desarrollo, `redis` para producción).
* **Rate Limiting:** Los límites están definidos en `bootstrap/app.php`. Puedes ajustarlos en la sección `using` de `withRouting`.

---

## 5. Ejecución del Proyecto

Para levantar el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

---

## 6. Endpoints de la API

Aquí se listan los principales endpoints de la API. La API debe devuelve los datos en formato JSON.


- Base URL: http://127.0.0.1:8000/api
- Método	Endpoint	Descripción	Autenticación
- GET	/products	Obtener lista de productos. Soporta paginación y filtros.	Opcional
- POST	/products	Crear un nuevo producto.	Requerida
- GET	/products/{id}	Obtener un producto por ID.	Opcional
- PUT	/products/{id}	Actualizar un producto.	Requerida
- DELETE	/products/{id}	Eliminar un producto.	Requerida
- GET	/products/{id}/prices	Obtener lista de precios de un producto.	Opcional
- POST	/products/{id}/prices	Crear un nuevo precio para un producto.	Requerida
- GET	/currencies	Obtener lista de divisas.	Opcional
- POST	/auth/login	Iniciar sesión y obtener token Sanctum	No



## 7. Autenticación (Sanctum)

La API utiliza Laravel Sanctum para la autenticación de APIs

Flujo de Autenticación:

    Inicio de Sesión y Obtención de Token:
        Endpoint: POST /api/auth/login
        Body: {"email": "test@example.com", "password": "password"}
        Respuesta (200 OK): { "message": "Logged in", "token": "tu_token_sanctum_aqui" }
            Este token debe ser almacenado por el cliente.
    Uso del Token para Peticiones Protegidas:
        Incluye el token en el header Authorization de todas las peticiones a endpoints protegidos.
        Header: Authorization: Bearer <tu_token_sanctum_aqui>

## 8. Documentación de la API

La API debe tener una documentación clara y concisa. Se cuenta con documentación interactiva generada con Swagger (OpenAPI).

Para acceder a la documentación de Swagger:

    Asegúrate de que el servidor de Laravel esté corriendo (php artisan serve).
    Accede a la siguiente URL en tu navegador: http://127.0.0.1:8000/api/documentation

También se proporcionan archivos de colección para Postman e Insomnia para facilitar las pruebas manuales de los endpoints.

