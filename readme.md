Republikový web Svobodných [![Build Status](https://secure.travis-ci.org/svobodni/web.png)](http://travis-ci.org/svobodni/web)
==========================

Instalace
---------

1) naklonujte Git repozitář:

```sh
git clone git@github.com:svobodni/web.git
```

2) nastavte databázi a účet administrátora v souboru `app/config/config.local.neon`:

```yml
parameters:
	database:
		user: ''
		password: ''
		dbname: ''
		hostname: 127.0.0.1

cms:
	administration:
		login:
			name: admin
			password: pass
```

3) vytvořte strukturu databáze pomocí příkazu:

```sh
php www/index.php orm:schema-tool:update --force
```

4) stáhněte a importujte fiktivní obsah:

```sh
curl http://web.svobodni.cz/scripts/export-db.php > app/data/deployment/init\@pdo_mysql\@$(date +"%Y-%m-%d_%H:%M:%S").sql
php www/index.php deployment:import init
```

Hotovo :)
