# Étape 1 : Build de l'app Vue avec Node
FROM node:20 AS builder

# Définit le répertoire de travail
WORKDIR /app

# Copie les fichiers package.json et installe les dépendances
COPY package*.json ./
RUN npm install

# Copie le reste du code et build l'app
COPY . .
RUN npm run build

# Étape 2 : Serve les fichiers statiques avec 'serve' (sans Nginx)
FROM node:20-alpine

# Installe 'serve' globalement (serveur HTTP statique minimal)
RUN npm install -g serve

# Définit le répertoire de travail
WORKDIR /app

# Copie les fichiers buildés depuis l'étape précédente
COPY --from=builder /app/dist ./dist

# Expose le port 3000 (serveur interne ; Traefik proxy vers celui-ci)
EXPOSE 3000

# Commande de démarrage : sert les fichiers statiques sur port 3000
CMD ["serve", "-s", "dist", "-l", "3000"]