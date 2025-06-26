## Proyecto Inventario API con Laravel 12

Este proyecto es una API RESTful construida con Laravel 12, que gestiona usuarios, categorías y productos, e incluye autenticación con Sanctum, control de acceso por roles, uso de patrones de diseño (Repository y Service) y documentación Swagger.

---

## 1. Instrucciones para configurar localmente

1. Clona el repositorio:
```bash
git clone https://github.com/tuusuario/inventario-api.git
cd inventario-api
```

2. Instala las dependencias:
```bash
composer install
```

3. Crear la base de datos desde tu cliente MySQL:
```sql
CREATE DATABASE inventario;
```

4. Copia el archivo `.env.example` a `.env` y configura tu conexión a la base de datos:
```bash
cp .env.example .env
```

5. Genera la clave de la aplicación:
```bash
php artisan key:generate
```

6. Ejecuta las migraciones:
```bash
php artisan migrate
```

7. Inicia el servidor de desarrollo:
```bash
php artisan serve
```

8. (Opcional) Genera la documentación Swagger:
```bash
php artisan l5-swagger:generate
```

---

## 2. Cómo importar y usar la colección Postman o el archivo Swagger

### 📦 Colección Postman
- Abre Postman y haz clic en **Importar**
- Selecciona el archivo `inventario.postman_collection.json` que se encuentra en la raiz del 
proyecto
- todos los endpoints excepto login regquieren de 'token' el cual se genera al consumir el endpoint
de login
- el token se adiciona en la pestaña Authorization/ Auth Type(bearer token) y en la variable Token en 
el campo de texto se adiciona el valor del token que aparece en la respuesta del login


---

## 3. URL pública de despliegue


---

## 4. Decisiones de diseño

### 🧩 Enum vs tabla de roles
- Se optó por usar un campo `role` de tipo `enum('admin', 'user')` para simplificar la lógica y evitar joins adicionales. Esta elección es suficiente para un sistema con 2 roles fijos.

### 🛡️ Middleware de autorización
- Se implementó un middleware `access` para proteger rutas sensibles (crear, actualizar, eliminar).
- Además, se usa `auth:sanctum` para proteger endpoints que requieren autenticación.

### 🧱 Cambios al esquema de base de datos
- La tabla `users` incluye el campo `role` como `enum`.
- Se implementaron relaciones `products -> category_id` y `categories` puede incluir sus productos relacionados.

### 📐 Patrones de diseño
- **Repository Pattern**: utilizado para desacoplar el acceso a datos.
- **Service Pattern**: encapsula la lógica de negocio fuera de los controladores.
- Justificación: facilita pruebas, mantenimiento y escalabilidad del proyecto.

---
