# Doctrine ORM 3 / DBAL 4 Compatibility Tracking

## Goal

Track every task required to make all bundles under `/lib` compatible with Doctrine ORM 3.x and Doctrine DBAL 4.x.

Primary references:

- ORM 3.6 upgrade guide: <https://github.com/doctrine/orm/blob/3.6.x/UPGRADE.md>
- DBAL 4.4 upgrade guide: <https://github.com/doctrine/dbal/blob/4.4.x/UPGRADE.md>

Recommended execution order from Doctrine:

1. Upgrade to DBAL 3
2. Upgrade to ORM 3
3. Upgrade to DBAL 4

## Legend

Statuses:

- `TODO`: not reviewed yet
- `DONE`: reviewed and fixed if needed
- `N/A`: not applicable for this bundle
- `BLOCKED`: needs a decision or third-party upgrade first

Bundle codes:

- `EAU`: easy-admin-user-bundle
- `EBL`: easy-block-bundle
- `EBG`: easy-blog-bundle
- `ECO`: easy-common-bundle
- `ECF`: easy-config-bundle
- `EED`: easy-editor-bundle
- `EFAQ`: easy-faq-bundle
- `EFLD`: easy-fields-bundle
- `EMD`: easy-media-bundle
- `EMN`: easy-menu-bundle
- `EPG`: easy-page-bundle
- `ERD`: easy-redirect-bundle
- `ESEO`: easy-seo-bundle

## Global decisions

| Decision | Status | Notes |
| --- | --- | --- |
| Root sandbox must target `doctrine/doctrine-bundle >= 2.15` | DONE | Root `composer.json` now allows `^2.15`; installed sandbox still resolves on DoctrineBundle `2.18.2` under Symfony `7.3.11` |
| Root sandbox must target ORM 3.6.x and DBAL 4.4.x | DONE | Root `composer.json` now requires `doctrine/orm ^3.6` and `doctrine/dbal ^4.4`; lock rebuilt to ORM `3.6.2` and DBAL `4.4.2` |
| Upgrade order will follow DBAL 3 -> ORM 3 -> DBAL 4 | DONE | Sandbox started on DBAL `3.10.4`; lot 1 completed the dependency gate to ORM `3.6.2` and DBAL `4.4.2` |
| PostgreSQL `GeneratedValue(strategy: "AUTO")` policy must be chosen | BLOCKED | No PostgreSQL target contract or rollout environment is defined in this sandbox; choosing `IDENTITY` vs `SEQUENCE` here would be arbitrary |
| Third-party Doctrine integrations must be verified | DONE | Verified and aligned minima for EasyAdmin `^4.10`, Pagerfanta ORM adapter `^4.3`, Stof `^1.15`, Gedmo `^3.21`; DBAL 4 bootstrap also required removing the unused Gedmo Loggable mapping from root config |

## Root sandbox prerequisites

| Task | Status | Notes |
| --- | --- | --- |
| Bump Doctrine package constraints in root `composer.json` | DONE | Root constraints updated to DoctrineBundle `^2.15`, ORM `^3.6`, DBAL `^4.4`, plus `carbonphp/carbon-doctrine-types ^3.2` to unblock DBAL 4 through `nesbot/carbon` |
| Validate Symfony 7 + DoctrineBundle minimum compatibility | DONE | Checked against Composer metadata: DoctrineBundle `2.15.2+` supports Symfony `^6.4 || ^7.0` and ORM `^2.17 || ^3.1` in its dev matrix |
| Rebuild lock file after dependency decisions | DONE | `composer update doctrine/doctrine-bundle doctrine/orm doctrine/dbal easycorp/easyadmin-bundle stof/doctrine-extensions-bundle pagerfanta/doctrine-orm-adapter carbonphp/carbon-doctrine-types --with-all-dependencies` completed successfully |
| Run full sandbox smoke tests on upgraded dependencies | BLOCKED | Lot 3 completed `composer validate --strict`, `php bin/console about`, `php bin/console doctrine:mapping:info --env=test`, and a full PHPUnit run; the only remaining blocker is `php bin/console doctrine:schema:validate --env=test`, which still reports SQLite drift under DBAL 4 even after switching tracked config away from `url`, creating a fresh SQLite schema, and retrying `doctrine:schema:update --force` |

## Cross-bundle task matrix

### Dependency and platform tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DEP-01 | Verify bundle composer constraints against ORM 3 / DBAL 4 ecosystem | ORM general, DBAL general | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Raised published minima where needed: EasyAdmin `^4.10`, Pagerfanta ORM adapter `^4.3`, Stof `^1.15`, Gedmo `^3.21` |
| DEP-02 | Verify Doctrine-related third-party packages used by the bundle | ORM general, DBAL general | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Composer metadata and upgraded sandbox validated for EasyAdmin, Pagerfanta ORM adapter, Stof/Gedmo, and ResetPassword integration; unused Gedmo Loggable mapping removed from root config |
| DEP-03 | Confirm no bundle depends on APIs removed only in DBAL 4 | DBAL 4.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | `rg` scan across `src/`, `lib/`, `tests/`, and `config/` found no `Connection::exec()`, `executeUpdate()`, `query()`, `Result::fetch()/fetchAll()`, `PARAM_*_ARRAY`, or schema-manager API usages in scope |

### ORM 3 mapping and metadata tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| ORM-01 | Check SQL default expressions and replace string defaults with `DefaultExpression` objects | ORM 3.6 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Reviewed all tracked entity mappings; only scalar defaults remain (`''`, `false`) and no `CURRENT_*` string defaults are declared |
| ORM-02 | Check `FieldMapping::$default` assumptions and rely on `options["default"]` only | ORM 3.6 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No bundle code reads `FieldMapping::$default`; metadata scans only found ORM 3-safe object API usage in listener tests |
| ORM-03 | Check join columns used in primary keys are never nullable | ORM 3.6 | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | No bundle defines join-column primary keys, composite id associations, or many-to-many id mappings |
| ORM-04 | Validate all `loadClassMetadata` dynamic mappings against ORM 3 metadata rules | ORM 3.x | DONE | N/A | DONE | N/A | N/A | N/A | DONE | N/A | DONE | DONE | DONE | N/A | N/A | Lot 2 validated listeners with real ORM `ClassMetadata` tests; EMN listener also moved invalid `orderBy` from a join column payload to the `children` one-to-many mapping |
| ORM-05 | Check there is no undeclared entity inheritance | ORM 3.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No entity inheritance hierarchies were found; bundles only use explicit mapped superclasses plus concrete app entities |
| ORM-06 | Check there is no field or association override outside mapped superclasses | ORM 3.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No `AttributeOverride` or `AssociationOverride` usage was found in tracked code |
| ORM-07 | Check embeddables do not use forbidden entity-level attributes or lifecycle callbacks | ORM 3.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Reviewed `Adeliom\\EasySeoBundle\\Entity\\SEO` and its consumers; no embeddable carries entity-level callbacks or inheritance metadata |
| ORM-08 | Check discriminator maps for duplicate classes | ORM 3.4 | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | No discriminator maps are declared anywhere in the repo |
| ORM-09 | Check code does not use array access on mapping objects | ORM 3.1 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No production code uses array access on mapping objects; listener coverage asserts ORM 3 object accessors instead |
| ORM-10 | Check code does not use removed metadata classes or drivers | ORM 3.0-3.3 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Scans found no remaining `ClassMetadataInfo`, `AnnotationDriver`, `DatabaseDriver`, or `ReflectionEnumProperty` usage |

### ORM 3 query and event tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| ORM-11 | Check DQL does not use `PARTIAL`, `HINT_FORCE_PARTIAL_LOAD`, or `getPartialReference()` | ORM 3.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No `PARTIAL`, `HINT_FORCE_PARTIAL_LOAD`, or `getPartialReference()` usage was found in tracked PHP code |
| ORM-12 | Check `QueryBuilder::setParameters()` never receives a native array | ORM 3.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No Doctrine `QueryBuilder::setParameters()` calls were found; remaining `setParameters(array)` hits belong to controller test doubles, not Doctrine query builders |
| ORM-13 | Check `QueryBuilder::add("join", ...)` is not used with join-part lists | ORM 3.6 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No `QueryBuilder::add("join", ...)` usage was found |
| ORM-14 | Check arbitrary DQL joins use `ON` instead of `WITH` | ORM 3.6 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No arbitrary DQL join using `WITH` was found in repositories or tests |
| ORM-15 | Check update/delete query builders always pass alias explicitly | ORM 3.0 | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | No DQL `update()` or `delete()` query builders exist in scope |
| ORM-16 | Check code does not rely on removed `QueryBuilder` constants or state methods | ORM 3.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No removed QueryBuilder constants/state methods were found; `getState()` hits belong to entities and enums, not Doctrine query builders |
| ORM-17 | Check Doctrine events do not use removed `LifecycleEventArgs` | ORM 3.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | All Doctrine listeners/subscribers already use dedicated ORM 3 event arg classes such as `LoadClassMetadataEventArgs`, `PostPersistEventArgs`, and `PostUpdateEventArgs` |
| ORM-18 | Check `OnClearEventArgs` and `*FlushEventArgs` do not call `getEntityManager()` | ORM 3.0 | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | No `OnClearEventArgs`, `PreFlushEventArgs`, or `PostFlushEventArgs` consumers were found |
| ORM-19 | Check there are no custom walkers or persisters relying on removed ORM 3 APIs | ORM 3.0-3.3 | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | No custom walkers, tree walkers, or persisters are implemented in the repo |
| ORM-20 | Decide and apply PostgreSQL identity generation strategy for `AUTO` | ORM 3.0 | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | Still blocked globally: no PostgreSQL target contract or rollout environment is defined, so changing `AUTO` to `IDENTITY` or `SEQUENCE` would be arbitrary |

### DBAL 4 tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DBAL-01 | Replace removed `Connection::exec()`, `executeUpdate()`, and `query()` usage | DBAL 4.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Repo-wide scans found no remaining `Connection::exec()`, `executeUpdate()`, or DBAL `query()` usage in tracked PHP/config code |
| DBAL-02 | Replace removed `Result::fetch()` and `fetchAll()` usage | DBAL 4.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Repo-wide scans found no `Result::fetch()` or `fetchAll()` calls |
| DBAL-03 | Replace removed `Connection::PARAM_*_ARRAY` constants | DBAL 4.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No `Connection::PARAM_*_ARRAY` usage remains |
| DBAL-04 | Check there is no DBAL config relying on `url` connection parameter | DBAL 4.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Root Doctrine config now uses explicit connection parameters for MySQL plus a dedicated SQLite `path` override in `config/packages/test/doctrine.yaml`; no tracked config still relies on `url` |
| DBAL-05 | Check there is no incomplete `serverVersion` format | DBAL 4.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Root config now uses the full MariaDB version string `mariadb-10.4.31` |
| DBAL-06 | Replace deprecated schema manager introspection methods | DBAL 4.4 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Scans found no `listTableNames()`, `listTables()`, `listTableColumns()`, `listTableIndexes()`, or similar schema-manager introspection calls |
| DBAL-07 | Check no code extends deprecated/internal DBAL schema classes | DBAL 4.3-4.4 | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | No bundle defines subclasses of DBAL schema assets or diff classes |
| DBAL-08 | Check no code instantiates internal DBAL schema classes directly | DBAL 4.3-4.4 | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | N/A | No direct instantiation of DBAL internal schema classes was found |
| DBAL-09 | Replace deprecated `AbstractAsset` name and quoting APIs if used | DBAL 4.4 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | No `AbstractAsset` name/quoting APIs such as `getQuotedName()`, `isQuoted()`, or `quoteIdentifier()` are used in tracked code |
| DBAL-10 | Review `DateTime` and `BigInt` behavior changes | DBAL 4.0 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Review found no `bigint` mappings and no custom DBAL conversion logic beyond the already-tested EED/EMD types; full PHPUnit runs stayed green on DBAL 4.4 |
| DBAL-11 | Replace string current-date default expressions with DBAL `Current*` classes | DBAL 4.4 | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | Same sweep as ORM-01: no string `CURRENT_*` defaults are declared in tracked entity mappings |
| DBAL-12 | Review custom DBAL types against DBAL 4 interfaces and removed methods | DBAL 4.x | N/A | N/A | N/A | N/A | N/A | DONE | N/A | N/A | DONE | N/A | N/A | N/A | N/A | Lot 2 removed the obsolete `requiresSQLCommentHint()` override and static container access from EMD, kept EED on DBAL 4 `JsonType`, and covered both types with targeted PHPUnit tests |

### Validation tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| VAL-01 | Run bundle unit tests on upgraded dependencies | Internal | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | `php bin/phpunit` and `SYMFONY_DEPRECATIONS_HELPER=max[self]=0 php bin/phpunit` both passed: 352 tests, 1618 assertions |
| VAL-02 | Run metadata validation for the bundle inside the sandbox | Internal | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | BLOCKED | `php bin/console doctrine:mapping:info --env=test` passes with 37 mapped entities, but `php bin/console doctrine:schema:validate --env=test` remains blocked by a reproducible DBAL 4 SQLite diff on EasyBlog/EasyFaq/App media/EasyPage tables even after fresh `doctrine:schema:create` and `doctrine:schema:update --force` runs |
| VAL-03 | Add or update tests for dynamic mapping listeners if the bundle has one | Internal | DONE | N/A | DONE | N/A | N/A | N/A | DONE | N/A | DONE | DONE | DONE | N/A | N/A | Lot 2 replaced mock-only assertions with real `ClassMetadata` coverage for EAU, EBG, EFAQ, EMD, EMN, and EPG listeners, including idempotence checks on repeated `loadClassMetadata` calls |
| VAL-04 | Add or update tests for custom DBAL types if the bundle has one | Internal | N/A | N/A | N/A | N/A | N/A | DONE | N/A | N/A | DONE | N/A | N/A | N/A | N/A | Lot 2 added DBAL 4-oriented tests for EED JSON conversion behavior and updated EMD tests to assert resolver-only PHP conversion plus DB value serialization |
| VAL-05 | Confirm no Doctrine deprecations remain at runtime in upgraded sandbox | Internal | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | DONE | `SYMFONY_DEPRECATIONS_HELPER=max[self]=0 php bin/phpunit` completed cleanly under ORM 3.6 / DBAL 4.4 |

## Known hotspots already found in the repo

| Bundle | File | Why it matters | Status |
| --- | --- | --- | --- |
| Root | `composer.json` | Still targets DoctrineBundle `^2.7.0` and ORM `^2.13` | DONE |
| EMD | `lib/easy-media-bundle/src/Types/EasyMediaType.php` | Custom DBAL type still implements `requiresSQLCommentHint()` and uses static container access | DONE |
| EED | `lib/easy-editor-bundle/src/Types/EasyEditorType.php` | Custom DBAL type must be validated against DBAL 4 behavior | DONE |
| EAU | `lib/easy-admin-user-bundle/src/EventListener/DoctrineMappingListener.php` | Runtime mapping must be validated on ORM 3 | DONE |
| EBG | `lib/easy-blog-bundle/src/EventListener/DoctrineMappingListener.php` | Runtime mapping must be validated on ORM 3 | DONE |
| EFAQ | `lib/easy-faq-bundle/src/EventListener/DoctrineMappingListener.php` | Runtime mapping must be validated on ORM 3 | DONE |
| EMD | `lib/easy-media-bundle/src/EventListener/DoctrineMappingListener.php` | Runtime mapping must be validated on ORM 3 | DONE |
| EMN | `lib/easy-menu-bundle/src/EventListener/DoctrineMappingListener.php` | Runtime mapping must be validated on ORM 3 | DONE |
| EPG | `lib/easy-page-bundle/src/EventListener/DoctrineMappingListener.php` | Runtime mapping must be validated on ORM 3 | DONE |
| EMN | `lib/easy-menu-bundle/src/Repository/MenuItemRepository.php` | Depends on Gedmo tree repository compatibility with ORM 3 / DBAL 4 | DONE |

## Suggested review order

1. Lock root dependency targets and third-party package compatibility.
2. Fix custom DBAL types.
3. Fix dynamic Doctrine mapping listeners and related tests.
4. Sweep query, event, and metadata API removals.
5. Sweep DBAL 4 query and schema API removals.
6. Run validation commands and test suites.
7. Mark each matrix cell as `DONE`, `N/A`, or `BLOCKED`.

## Validation checklist

| Command or checkpoint | Status | Notes |
| --- | --- | --- |
| `composer update` with target Doctrine versions | DONE | Completed in lot 1 with DoctrineBundle `2.18.2`, ORM `3.6.2`, and DBAL `4.4.2` locked in the sandbox |
| `php bin/console doctrine:mapping:info` | DONE | Lot 3 reran `--env=test`; it passes with 37 mapped entities reported |
| `php bin/console doctrine:schema:validate` | BLOCKED | `--env=test` still reports schema drift under SQLite/DBAL 4 after switching tracked config away from `url`, recreating a fresh SQLite schema, and retrying `doctrine:schema:update --force`; the remaining diff keeps rebuilding EasyBlog/EasyFaq/App media/EasyPage tables |
| Targeted PHPUnit suites for touched bundles | DONE | Lot 2 listener/type coverage remains green and lot 3 extended validation to the full sandbox suite |
| Full sandbox PHPUnit run | DONE | `php bin/phpunit` passed: 352 tests, 1618 assertions |
| Runtime deprecation scan clean for Doctrine | DONE | `SYMFONY_DEPRECATIONS_HELPER=max[self]=0 php bin/phpunit` passed with no runtime deprecations from first-party code |
