# API-Platform


## Installation 

#### Récupération du projet
```bash
git clone https://github.com/BouBooo/API-Platform.git
```

#### Installation des packages
```bash
composer install
```

#### Création de la base de données
```bash
php bin/console doctrine:schema:create 
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


