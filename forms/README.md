# Sistema de Contacto - Portfolio Oskar Marijuan

## ✅ Instalación Rápida

### Paso 1: Crear la base de datos
Accede a esta URL en tu navegador:
```
http://localhost:8888/Portfolio/forms/db_init.php
```
Verás un mensaje de confirmación cuando la BD se cree exitosamente.

Alternativa manual (phpMyAdmin):
1. Abre http://localhost:8888/phpMyAdmin
2. Copia el contenido de `forms/setup_database.sql`
3. Pega en la pestaña "SQL" y ejecuta

### Paso 2: ¡Listo!
El formulario de contacto ya está funcionando. Los datos se guardarán en la BD automáticamente.

---

## 📧 Ver Mensajes Recibidos

**Acceso a panel de administración:**
```
http://localhost:8888/Portfolio/forms/view_messages.php
```

Desde aquí puedes:
- Ver todos los mensajes recibidos
- Marcar como leído/no leído
- Ver detalle completo de cada mensaje
- Responder por email

---

## 🛠️ Archivos Incluidos

| Archivo | Descripción |
|---------|-----------|
| `contact.php` | Procesa el formulario, valida datos y guarda en BD |
| `db_init.php` | Script para crear la BD automáticamente |
| `setup_database.sql` | Script SQL para crear BD manualmente |
| `view_messages.php` | Panel para ver todos los mensajes |
| `view_message.php` | Detalle de cada mensaje |

---

## 📋 Campos del Formulario

El formulario recibe:
- **name** (texto, min 4 caracteres) — Nombre del remitente
- **email** (email válido) — Email de contacto
- **subject** (texto, min 4 caracteres) — Asunto del mensaje
- **message** (texto) — Cuerpo del mensaje

---

## 🔒 Seguridad

✅ **Validación en servidor** — Todos los datos se validan en PHP  
✅ **Escape de SQL** — Previene inyecciones SQL  
✅ **UTF-8** — Soporta caracteres especiales correctamente  
✅ **CSRF-safe** — El formulario está protegido contra CSRF

---

## 💾 Base de Datos

### Tabla: `messages`
```sql
- id (INT, PK, Auto-increment)
- name (VARCHAR 255)
- email (VARCHAR 255)
- subject (VARCHAR 255)
- message (LONGTEXT)
- ip_address (VARCHAR 45)
- user_agent (TEXT)
- created_at (DATETIME, timestamp)
- read_status (TINYINT, 0=no leído, 1=leído)
- replied_at (DATETIME, nullable)
```

---

## 📧 Envío de Emails (Opcional)

Actualmente, `contact.php` envía un email de confirmación al cliente usando `mail()` de PHP.

**Para mejorar la entrega:**

1. **Usar SMTP (recomendado):**
   Edita `contact.php` y usa una librería como PHPMailer:
   ```php
   composer require phpmailer/phpmailer
   ```

2. **Configurar SMTP en MAMP:**
   - Edita `/Applications/MAMP/conf/apache/php.ini`
   - Configura `[mail function]` con tus credenciales SMTP

3. **Usar un servicio externo:**
   - SendGrid
   - Mailgun
   - Amazon SES

---

## 🚀 Deploy en Firebase/GitHub

Los archivos PHP (`.php`) **NO se ejecutarán en GitHub Pages** (es estático).

**Opciones para producción:**

1. **Backend separado:**
   - Hostear `forms/contact.php` en un servidor PHP (Heroku, AWS, DigitalOcean, etc.)
   - Cambiar la URL `action` del formulario a ese servidor

2. **Cloud Function (Firebase):**
   - Reemplazar `contact.php` con una Cloud Function en Node.js
   - Llamarla desde AJAX en el formulario

3. **Servicio externo:**
   - Usar Formspree, EmailJS o similar (no requiere servidor propio)

---

## 🐛 Solucionar Problemas

### Error: "Connection failed"
- Verifica que MAMP está corriendo (MySQL activo)
- Verifica credenciales en `contact.php` (usuario/contraseña)

### Error: "Unknown database"
- Ejecuta `db_init.php` para crear la BD

### Los emails no se envían
- La función `mail()` requiere SMTP configurado
- Usa `view_messages.php` para ver los mensajes (se guardan igual)

### El formulario no responde
- Abre la consola del navegador (F12) para ver errores
- Verifica que `validate.js` está cargado

---

## 📞 Contacto
Para preguntas o issues, revisa el código en `forms/contact.php`.

---

**Última actualización:** 1 de diciembre de 2025
