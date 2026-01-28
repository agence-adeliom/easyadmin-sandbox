### Installation

``ddev start``

``ddev composer install``

``ddev console doc:mig:mig``

``ddev console doctrine:fixtures:load -q``

``ddev npm install``

### Manage releases

Run the release script to create a new version:

```bash
./release.sh
```

This will:
1. Detect the latest `3.0.x` tag and calculate the next version
2. Update all internal agence-adeliom dependencies from `^3.1` to the release version
3. Commit as "prepare release"
4. Revert dependencies back to `^3.1`
5. Commit as "open 3.1.x-dev"
6. Create the tag on the "prepare release" commit

After the script completes, verify the changes and push:

```bash
git push origin 3.x
git push origin 3.0.X  # replace X with the new version number
```

### PHPUnit tests

```
ddev console d:m:m --env=test -n
ddev console doc:fixtures:load --env=test -n
ddev composer unit-tests
```
