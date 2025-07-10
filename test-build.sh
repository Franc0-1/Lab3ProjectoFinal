#!/bin/bash

echo "🔨 Testing Docker build locally..."

# Limpiar builds anteriores
echo "🧹 Cleaning previous builds..."
docker system prune -f

# Construir la imagen
echo "📦 Building Docker image..."
docker build -t laqueva-test .

if [ $? -eq 0 ]; then
    echo "✅ Docker build successful!"
    
    # Verificar que los assets se construyeron
    echo "🔍 Checking if assets were built..."
    docker run --rm laqueva-test ls -la /var/www/html/public/build/assets/
    
    if [ $? -eq 0 ]; then
        echo "✅ Assets built successfully!"
    else
        echo "❌ Assets not found!"
    fi
else
    echo "❌ Docker build failed!"
    exit 1
fi

echo "🎉 All tests passed! Ready for deployment."
