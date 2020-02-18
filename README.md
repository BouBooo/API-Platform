# API-Platform


## Installation 

#### Récupération du projet
```bash
git clone https://github.com/BouBooo/API-Platform.git
```

#### Se rendre dans le projet
```bash
cd API-Platform
```

#### Installation des packages
```bash
composer install
```

#### Création de la base de données
```bash
php bin/console doctrine:database:create 
```

#### Création de la structure de la base
```bash
php bin/console doctrine:schema:update -f
```

#### Chargement des données
```bash
php bin/console doctrine:fixtures:load -n
```

#### Lancement du serveur
```bash
php bin/console server:run
```


