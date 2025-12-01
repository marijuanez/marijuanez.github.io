#!/bin/bash
# Script para desplegar automáticamente a Firebase vía GitHub Actions
# Uso: ./deploy.sh "mensaje de commit"

set -e

# Colores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Desplegando Portfolio a Firebase...${NC}"

# 1. Verificar que estamos en el directorio correcto
if [ ! -d ".git" ]; then
    echo "❌ Error: No estás en el directorio del repositorio"
    exit 1
fi

# 2. Verificar estado de git
if [[ -n $(git status -s) ]]; then
    echo -e "${BLUE}📝 Hay cambios sin commitear. Añadiendo archivos...${NC}"
    git add .
    
    # Usar mensaje personalizado o uno por defecto
    if [ -z "$1" ]; then
        COMMIT_MSG="chore: update portfolio $(date '+%Y-%m-%d %H:%M')"
    else
        COMMIT_MSG="$1"
    fi
    
    git commit -m "$COMMIT_MSG"
    echo -e "${GREEN}✅ Commit creado${NC}"
else
    echo -e "${BLUE}ℹ️  No hay cambios locales. Forzando despliegue...${NC}"
    git commit --allow-empty -m "chore: force deployment $(date '+%Y-%m-%d %H:%M')"
fi

# 3. Push a GitHub (dispara GitHub Actions)
echo -e "${BLUE}📤 Empujando a GitHub...${NC}"
git push origin master:main

# 4. Confirmar
echo -e "${GREEN}✅ Push exitoso!${NC}"
echo ""
echo -e "${BLUE}📊 Verifica el despliegue en:${NC}"
echo "   GitHub Actions: https://github.com/marijuanez/marijuanez.github.io/actions"
echo "   Sitio web: https://marijuanez.web.app/"
echo ""
echo -e "${BLUE}⏱️  El despliegue tardará ~2-5 minutos${NC}"
