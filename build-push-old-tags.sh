#!/bin/bash

set -e

IMAGE="mariama0022/reservation-salles-app"
DOCKERFILE="/tmp/Dockerfile-reservation-salles"

echo "📦 Récupération du Dockerfile actuel..."
cp Dockerfile "$DOCKERFILE"

for TAG in v0.1.0 v0.2.0 v0.3.0 v0.4.0 v0.5.0 v0.6.0 v0.7.0 v0.8.0
do
    DIR="/tmp/reservation-salles-$TAG"

    echo ""
    echo "========================================"
    echo "🚀 Traitement de $TAG"
    echo "========================================"

    rm -rf "$DIR"
    mkdir -p "$DIR"

    echo "📂 Extraction de $TAG..."
    git archive "$TAG" | tar -x -C "$DIR"

    echo "🐳 Ajout du Dockerfile..."
    cp "$DOCKERFILE" "$DIR/Dockerfile"

    echo "🔨 Construction de $IMAGE:$TAG..."
    docker build -t "$IMAGE:$TAG" "$DIR"

    echo "📤 Publication de $IMAGE:$TAG..."
    docker push "$IMAGE:$TAG"

    echo "✅ $TAG terminé !"
done

echo ""
echo "🎉 Toutes les versions v0.1.0 à v0.8.0 ont été publiées !"
