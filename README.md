## Developer Installation

By default, the database is sqlite.  For production, set to an appropriate database and create migrations, etc.

```bash
git clone git@github.com:tacman/easyadmin-sandbox.git ezsand && cd ezsand
composer install
bin/console doctrine:fixtures:load -n 

symfony server:start -d
symfony open:local --path=/en/admin


```

### Installation

``ddev start``

``ddev composer install``

``ddev console doc:mig:mig``

``ddev console doctrine:fixtures:load -q``

``ddev npm install``

### Manage releases

Read https://github.com/symplify/monorepo-builder

https://github.com/sym

``ddev lint-back``

Execute follow outside your container (php 8.0 is required) and run

``vendor/bin/monorepo-builder release patch --dry-run``

If dry run execution is successfull run :

``vendor/bin/monorepo-builder release patch``

### PHPUnit tests

Read https://github.com/symplify/monorepo-builder

https://github.com/sym

```
ddev console d:m:m --env=test -n
ddev console doc:fixtures:load --env=test -n
ddev composer unit-tests
``
