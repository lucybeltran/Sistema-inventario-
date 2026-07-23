# Sistema de Gestión de Inventario — Sección Catalina (Empresa Minera Torrez S.R.L.)

Este repositorio contiene el **Sistema de Gestión de Inventario** desarrollado en Laravel. A continuación se presentan las instrucciones detalladas paso a paso para clonar e instalar este proyecto en cualquier otro dispositivo sin errores.

---

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado en el dispositivo de destino:

1. **PHP >= 8.2** (con las extensiones requeridas por Laravel: `mbstring`, `openssl`, `pdo`, `xml`, `zip`, etc.)
2. **Composer** (Manejador de dependencias de PHP)
3. **Node.js** (versión LTS recomendada) y **NPM**
4. **Servidor de Base de Datos MySQL/MariaDB** (por ejemplo, a través de XAMPP, Laragon, Docker o una instalación local directa)
5. **Git** (para clonar y gestionar el repositorio)

---

## 🚀 Pasos para la Instalación y Puesta en Marcha

Sigue estos pasos en el orden indicado:

### 1. Clonar el Repositorio
Abre una terminal o consola de comandos en la carpeta donde deseas guardar el proyecto y ejecuta:
```bash
git clone <URL_DEL_REPOSITORIO> "sistema-inventario"
```
*(Reemplaza `<URL_DEL_REPOSITORIO>` por el enlace HTTPS o SSH de tu repositorio en GitHub/GitLab)*

Ingresa a la carpeta del proyecto:
```bash
cd "sistema-inventario"
```

### 2. Instalar Dependencias de PHP
Ejecuta el siguiente comando para descargar e instalar todas las librerías necesarias del backend (incluyendo Laravel Framework y los paquetes de exportación):
```bash
composer install
```

### 3. Instalar Dependencias de Frontend (Node.js)
Instala los paquetes de Javascript necesarios para Vite y TailwindCSS:
```bash
npm install
```

### 4. Configurar el Archivo de Entorno (`.env`)
Laravel utiliza el archivo `.env` para almacenar credenciales seguras y configuraciones específicas del dispositivo local.
1. Copia el archivo de plantilla de ejemplo:
   - **En Windows (PowerShell/CMD):**
     ```powershell
     copy .env.example .env
     ```
   - **En Linux/macOS:**
     ```bash
     cp .env.example .env
     ```
2. Abre el archivo `.env` recién creado en un editor de texto y configura las variables de conexión a tu base de datos local:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306         # Cambia al puerto de tu MySQL local (ej: 3308 en XAMPP si lo modificaste)
   DB_DATABASE=sistema_inventario
   DB_USERNAME=root     # Tu usuario de MySQL
   DB_PASSWORD=         # Tu contraseña de MySQL (vacío por defecto en XAMPP/Laragon)
   ```

### 5. Generar la Clave Única de la Aplicación
Genera la clave cifrada de seguridad requerida por Laravel para encriptar sesiones y cookies:
```bash
php artisan key:generate
```

### 6. Configurar la Base de Datos
1. Abre tu gestor de base de datos preferido (phpMyAdmin, DBeaver, HeidiSQL o consola MySQL) y **crea una base de datos vacía** llamada `sistema_inventario` (o el nombre que configuraste en tu `.env`).
2. Una vez creada, elige una de las siguientes opciones para poblar la base de datos:
   - **Opción A (Migrar desde Cero con Datos Iniciales):**
     Si quieres crear las tablas vacías e insertar los roles, permisos y usuarios iniciales definidos en el sistema:
     ```bash
     php artisan migrate --seed
     ```
   - **Opción B (Importar un Respaldo Existente):**
     Si deseas restaurar los datos reales acumulados, puedes importar el dump SQL más reciente (por ejemplo, `database_dump.sql` o un archivo de respaldo guardado en la carpeta de copias de seguridad) directamente en la base de datos vacía usando la interfaz de phpMyAdmin o la consola de comandos.

### 7. Crear el Enlace de Almacenamiento (Storage Link)
Crea el enlace simbólico para que los archivos subidos al servidor de almacenamiento privado sean accesibles desde la web pública:
```bash
php artisan storage:link
```

### 8. Compilar los Recursos del Frontend
Compila y procesa los estilos CSS y scripts JS con Vite:
- **Para desarrollo local activo (tiempo real):**
  ```bash
  npm run dev
  ```
- **Para compilar en producción (archivos finales optimizados):**
  ```bash
  npm run build
  ```

---

## 🏃 Servidores en Ejecución

Para trabajar en el entorno de desarrollo local, debes tener tres procesos corriendo (puedes abrirlos en terminales separadas):

1. **Servidor Web de Laravel (Servidor Backend):**
   ```bash
   php artisan serve
   ```
   *Acceso directo:* Abre tu navegador en [http://127.0.0.1:8000](http://127.0.0.1:8000)

2. **Compilador en Vivo de Vite (Frontend):**
   ```bash
   npm run dev
   ```

3. **Programador de Tareas (Laravel Scheduler):**
   *¡Muy importante!* El sistema cuenta con un sistema de autolimpieza que borra registros viejos de la bitácora automáticamente cada 24 horas. Para que esto funcione de forma local, ejecuta:
   ```bash
   php artisan schedule:work
   ```
   *(En producción, se debe agregar un cron job apuntando a `php artisan schedule:run` en el servidor web)*

---

## 🛠️ Solución de Problemas Comunes

- **Error: "SQLSTATE[HY000] [2002] Connection refused"**
  - Verifica que tu servicio de base de datos MySQL esté activo y ejecutándose.
  - Asegúrate de que el puerto (`DB_PORT`) en tu archivo `.env` sea el mismo que usa tu servidor local MySQL.
- **Error: "Internal Server Error 500" o páginas en blanco**
  - Asegúrate de haber ejecutado `php artisan key:generate`.
  - Corre `php artisan config:clear` y `php artisan cache:clear` para refrescar configuraciones guardadas en la caché.
- **Los cambios en la interfaz o estilos no se ven reflejados**
  - Corre `npm run build` para asegurar la compilación de recursos finales.
