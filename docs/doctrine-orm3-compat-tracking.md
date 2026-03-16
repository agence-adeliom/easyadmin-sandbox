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
| Run full sandbox smoke tests on upgraded dependencies | BLOCKED | `composer validate --strict` / `php bin/console about` still pending; `php bin/console doctrine:mapping:info --env=test` passes after lot 2, but `php bin/console doctrine:schema:validate --env=test` still fails because the committed SQLite test schema is stale (`ext_log_entries` leftover plus broad table rebuild diff across EasyAdmin/EasyBlog/EasyFaq/EasyMedia/EasyPage tables) |

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
| ORM-01 | Check SQL default expressions and replace string defaults with `DefaultExpression` objects | ORM 3.6 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Also required by DBAL 4.4 |
| ORM-02 | Check `FieldMapping::$default` assumptions and rely on `options["default"]` only | ORM 3.6 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Mostly relevant for runtime mapping listeners and tests |
| ORM-03 | Check join columns used in primary keys are never nullable | ORM 3.6 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Includes many-to-many join tables and id associations |
| ORM-04 | Validate all `loadClassMetadata` dynamic mappings against ORM 3 metadata rules | ORM 3.x | DONE | N/A | DONE | N/A | N/A | N/A | DONE | N/A | DONE | DONE | DONE | N/A | N/A | Lot 2 validated listeners with real ORM `ClassMetadata` tests; EMN listener also moved invalid `orderBy` from a join column payload to the `children` one-to-many mapping |
| ORM-05 | Check there is no undeclared entity inheritance | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Must be explicit now |
| ORM-06 | Check there is no field or association override outside mapped superclasses | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | ORM 3 throws now |
| ORM-07 | Check embeddables do not use forbidden entity-level attributes or lifecycle callbacks | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Review traits and embeddables if any |
| ORM-08 | Check discriminator maps for duplicate classes | ORM 3.4 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Deprecated now, error later |
| ORM-09 | Check code does not use array access on mapping objects | ORM 3.1 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `FieldMapping`, `JoinColumnMapping`, etc. |
| ORM-10 | Check code does not use removed metadata classes or drivers | ORM 3.0-3.3 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `ClassMetadataInfo`, `AnnotationDriver`, `DatabaseDriver`, `ReflectionEnumProperty` |

### ORM 3 query and event tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| ORM-11 | Check DQL does not use `PARTIAL`, `HINT_FORCE_PARTIAL_LOAD`, or `getPartialReference()` | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Partial objects removed except limited array hydration cases |
| ORM-12 | Check `QueryBuilder::setParameters()` never receives a native array | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Only `ArrayCollection<Parameter>` is allowed |
| ORM-13 | Check `QueryBuilder::add("join", ...)` is not used with join-part lists | ORM 3.6 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Replace with keyed join parts or fluent join methods |
| ORM-14 | Check arbitrary DQL joins use `ON` instead of `WITH` | ORM 3.6 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `WITH` stays only for association join filters |
| ORM-15 | Check update/delete query builders always pass alias explicitly | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `delete(Entity::class, "e")` and `update(Entity::class, "e")` |
| ORM-16 | Check code does not rely on removed `QueryBuilder` constants or state methods | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `SELECT`, `UPDATE`, `DELETE`, `getState()`, `getType()` |
| ORM-17 | Check Doctrine events do not use removed `LifecycleEventArgs` | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Use dedicated event arg classes |
| ORM-18 | Check `OnClearEventArgs` and `*FlushEventArgs` do not call `getEntityManager()` | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Use `getObjectManager()` |
| ORM-19 | Check there are no custom walkers or persisters relying on removed ORM 3 APIs | ORM 3.0-3.3 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Output walkers, tree walkers, entity persisters |
| ORM-20 | Decide and apply PostgreSQL identity generation strategy for `AUTO` | ORM 3.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Only relevant if PostgreSQL is a target platform |

### DBAL 4 tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| DBAL-01 | Replace removed `Connection::exec()`, `executeUpdate()`, and `query()` usage | DBAL 4.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Use `executeStatement()` or `executeQuery()` |
| DBAL-02 | Replace removed `Result::fetch()` and `fetchAll()` usage | DBAL 4.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Use `fetchAssociative()`, `fetchAllAssociative()`, etc. |
| DBAL-03 | Replace removed `Connection::PARAM_*_ARRAY` constants | DBAL 4.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Use `ArrayParameterType` |
| DBAL-04 | Check there is no DBAL config relying on `url` connection parameter | DBAL 4.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Use `DsnParser` if needed |
| DBAL-05 | Check there is no incomplete `serverVersion` format | DBAL 4.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Must use full `x.y.z` version strings |
| DBAL-06 | Replace deprecated schema manager introspection methods | DBAL 4.4 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `listTableNames()`, `listTables()`, etc. |
| DBAL-07 | Check no code extends deprecated/internal DBAL schema classes | DBAL 4.3-4.4 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `Schema`, `Table`, `View`, `Sequence`, `ColumnDiff`, `TableDiff`, `SchemaDiff`, `Index`, `Column`, `ForeignKeyConstraint`, `UniqueConstraint` |
| DBAL-08 | Check no code instantiates internal DBAL schema classes directly | DBAL 4.3-4.4 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Prefer editors where needed |
| DBAL-09 | Replace deprecated `AbstractAsset` name and quoting APIs if used | DBAL 4.4 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `getName()`, `getQuotedName()`, `isQuoted()`, `quoteIdentifier()` |
| DBAL-10 | Review `DateTime` and `BigInt` behavior changes | DBAL 4.0 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Mutable vs immutable handling and bigint casts |
| DBAL-11 | Replace string current-date default expressions with DBAL `Current*` classes | DBAL 4.4 | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Same review as ORM-01 |
| DBAL-12 | Review custom DBAL types against DBAL 4 interfaces and removed methods | DBAL 4.x | N/A | N/A | N/A | N/A | N/A | DONE | N/A | N/A | DONE | N/A | N/A | N/A | N/A | Lot 2 removed the obsolete `requiresSQLCommentHint()` override and static container access from EMD, kept EED on DBAL 4 `JsonType`, and covered both types with targeted PHPUnit tests |

### Validation tasks

| ID | Task | Ref | EAU | EBL | EBG | ECO | ECF | EED | EFAQ | EFLD | EMD | EMN | EPG | ERD | ESEO | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| VAL-01 | Run bundle unit tests on upgraded dependencies | Internal | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Existing PHPUnit suites under each bundle |
| VAL-02 | Run metadata validation for the bundle inside the sandbox | Internal | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | `doctrine:mapping:info`, `doctrine:schema:validate` |
| VAL-03 | Add or update tests for dynamic mapping listeners if the bundle has one | Internal | DONE | N/A | DONE | N/A | N/A | N/A | DONE | N/A | DONE | DONE | DONE | N/A | N/A | Lot 2 replaced mock-only assertions with real `ClassMetadata` coverage for EAU, EBG, EFAQ, EMD, EMN, and EPG listeners, including idempotence checks on repeated `loadClassMetadata` calls |
| VAL-04 | Add or update tests for custom DBAL types if the bundle has one | Internal | N/A | N/A | N/A | N/A | N/A | DONE | N/A | N/A | DONE | N/A | N/A | N/A | N/A | Lot 2 added DBAL 4-oriented tests for EED JSON conversion behavior and updated EMD tests to assert resolver-only PHP conversion plus DB value serialization |
| VAL-05 | Confirm no Doctrine deprecations remain at runtime in upgraded sandbox | Internal | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | TODO | Final acceptance gate |

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
| EMN | `lib/easy-menu-bundle/src/Repository/MenuItemRepository.php` | Depends on Gedmo tree repository compatibility with ORM 3 / DBAL 4 | TODO |

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
| `php bin/console doctrine:mapping:info` | DONE | `--env=test` passes after lot 2 with 37 mapped entities reported |
| `php bin/console doctrine:schema:validate` | BLOCKED | `--env=test` still reports schema drift because the committed SQLite test schema is outdated (`ext_log_entries` plus broad rebuilds on existing tables) |
| Targeted PHPUnit suites for touched bundles | DONE | Lot 2 executed targeted suites for EAU, EBG, EED, EFAQ, EMD, EMN, and EPG listeners/types: 21 tests, 71 assertions, all green |
| Full sandbox PHPUnit run | TODO | Final regression gate |
| Runtime deprecation scan clean for Doctrine | TODO | Final acceptance gate |
