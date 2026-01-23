### Installation

``lando start``

``lando composer install``

``lando console doc:mig:mig``

``lando console doctrine:fixtures:load -q``

``lando npm install``

### Manage releases

Run the release script to create a new version:

```bash
./release.sh
```

This will:
1. Detect the latest `2.0.x` tag and calculate the next version
2. Update all internal agence-adeliom dependencies from `^2.1` to the release version
3. Commit as "prepare release"
4. Revert dependencies back to `^2.1`
5. Commit as "open 2.1.x-dev"
6. Create the tag on the "prepare release" commit

After the script completes, verify the changes and push:

```bash
git push origin 2.x
git push origin 2.0.X  # replace X with the new version number
```

### PHPUnit tests

```
lando console d:m:m --env=test -n
lando console doc:fixtures:load --env=test -n
lando composer unit-tests
```
