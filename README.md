### Installation

``lando start``

``lando composer install``

``lando console doc:mig:mig``

``lando console doctrine:fixtures:load -q``

``lando npm install``

### Manage releases

Read https://github.com/symplify/monorepo-builder

https://github.com/sym

``lando lint-back``

Execute follow outside your container (php 8.0 is required) and run

``vendor/bin/monorepo-builder release patch --dry-run``

If dry run execution is successfull run :

``vendor/bin/monorepo-builder release patch``
