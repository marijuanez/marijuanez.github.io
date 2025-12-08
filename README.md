# Portfolio - Oskar Marijuan

Portfolio personal desplegado en Firebase Hosting con sistema de contacto.

## 🚀 Despliegue Automático

Este proyecto usa **GitHub Actions** para desplegar automáticamente a Firebase cada vez que haces push a la rama `main`.

### Forma Rápida (script automatizado)

```bash
# Desplegar todos los cambios locales
./deploy.sh "descripción de cambios"

# O sin mensaje (usa mensaje por defecto con timestamp)
./deploy.sh
```

### Forma Manual

```bash
# 1. Hacer cambios locales y commit
git add .
git commit -m "tu mensaje"

# 2. Empujar a GitHub (dispara despliegue automático)
git push origin master:main

# 3. Verificar en:
# - GitHub Actions: https://github.com/marijuanez/marijuanez.github.io/actions
# - Sitio: https://marijuanez.web.app/
```

## 📝 Sistema de Contacto

### Local (MAMP)

1. **Inicializar base de datos:**
   ```bash
   # Abrir en navegador:
   http://localhost:8888/Portfolio/forms/db_init.php
   ```

2. **Ver mensajes recibidos:**
   ```bash
   http://localhost:8888/Portfolio/forms/view_messages.php
   ```

3. **Probar formulario:**
   ```bash
   http://localhost:8888/Portfolio/#contact
   ```

### Producción (Firebase)

El formulario en producción usa **Cloud Functions + Firestore** (backend serverless).

- Endpoint: `https://marijuanez.web.app/api/contact`
- Función: `submitContact` (en `functions/index.js`)
- Base de datos: Firestore collection `contact_messages`

## 🛠️ Stack Tecnológico

- **Frontend:** HTML5, CSS3 (Bootstrap), JavaScript (jQuery)
- **Backend Local:** PHP + MySQL (MAMP)
- **Backend Producción:** Firebase Cloud Functions + Firestore
- **Hosting:** Firebase Hosting
- **CI/CD:** GitHub Actions
- **Versionado:** Git + GitHub (SSH)

## 📂 Estructura del Proyecto

```
Portfolio/
├── index.html              # Página principal
├── css/                    # Estilos
├── js/                     # Scripts (incluyendo validate.js)
├── images/                 # Imágenes y assets
├── forms/                  # Backend PHP local
│   ├── contact.php         # Handler del formulario (local)
│   ├── db_init.php         # Inicializar BD MySQL
│   ├── view_messages.php   # Panel admin mensajes
│   └── setup_database.sql  # Schema de BD
├── functions/              # Cloud Functions (producción)
│   ├── index.js            # submitContact function
│   └── package.json        # Dependencias
├── .github/workflows/      # GitHub Actions
│   └── firebase-hosting.yml
├── firebase.json           # Config Firebase
├── .firebaserc             # Proyecto Firebase
└── deploy.sh               # Script automatizado ✨
```

## 🔧 Configuración Inicial (ya completada)

<details>
<summary>Ver pasos de configuración</summary>

### 1. Git + SSH
```bash
# Generar clave SSH
ssh-keygen -t ed25519 -C "tu-email@example.com"

# Copiar clave pública
pbcopy < ~/.ssh/id_ed25519.pub

# Añadir en: GitHub → Settings → SSH and GPG keys

# Configurar remoto SSH
git remote set-url origin git@github.com:marijuanez/marijuanez.github.io.git
```

### 2. Firebase CLI
```bash
# Instalar
npm install -g firebase-tools

# Login
firebase login

# Generar token CI para GitHub Actions
firebase login:ci
# Copiar el token y añadirlo como secret en GitHub
```

### 3. GitHub Secrets
En GitHub → Settings → Secrets and variables → Actions, añadir:
- `FIREBASE_TOKEN`: token de `firebase login:ci`
- `FIREBASE_PROJECT_ID`: `portfolio-dev-local`

</details>

## 📊 Monitoreo

- **GitHub Actions:** https://github.com/marijuanez/marijuanez.github.io/actions
- **Firebase Console:** https://console.firebase.google.com/
- **Sitio Web:** https://marijuanez.web.app/

## 🐛 Solución de Problemas

### El formulario no funciona en local
```bash
# 1. Verifica que MAMP esté corriendo
# 2. Inicializa la BD: http://localhost:8888/Portfolio/forms/db_init.php
# 3. Limpia caché del navegador (Cmd+Shift+R)
```

### GitHub Actions falla
```bash
# Verifica los secretos en GitHub:
# Settings → Secrets and variables → Actions
# - FIREBASE_TOKEN debe estar presente
# - FIREBASE_PROJECT_ID = portfolio-dev-local
```

### El sitio no se actualiza después del deploy
```bash
# 1. Espera 2-5 minutos
# 2. Limpia caché del navegador
# 3. Verifica logs en GitHub Actions
```

## 📞 Contacto

- **Email:** oskar.marijuan@gmail.com
- **LinkedIn:** [linkedin.com/in/oscarmarijuan](https://www.linkedin.com/in/oscarmarijuan/)
- **GitHub:** [github.com/marijuanez](https://github.com/marijuanez)

---

**Última actualización:** Diciembre 2025

<!-- Deploy to oskar-marijuan.web.app -->
