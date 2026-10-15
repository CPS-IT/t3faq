# FAQ

Frequently Asked Questions. This package is an extension to the TYPO3 system.

### Installation

* This is a private package. Please add the repo url to your root composer json
  first.

```
composer config repositories.t3faq vcs git@github.com:CPS-IT/t3faq.git
```

```
composer require cpsit/t3faq
```

## Actions / Views

### List selected FAQs

Displays a list of selected FAQs items in frontend. If no, FAQs item ist
selected nothing will be displayed.

**Action**: listSelectedAction

**Cache**: Cacheable

### List FAQs

Displays a list of FAQs items in frontend sorted by weight. Editors are able to
filter the results by category.
Editors have the possibility to enable or disable the search form in front end.
**Important**: _Search and filters have to be done with Ajax_.

**Action**: listSelectedAction

**Cache**: Cacheable

## Code quality

Run the tools in the package's DDEV project:

```
ddev composer lint   # composer.json, EditorConfig, PHP-CS-Fixer, TypoScript
ddev composer fix    # fix the findings that can be fixed automatically
ddev composer sca    # PHPStan, level 6
```

## Tests

Tests and code quality tools run in the package's own DDEV project
(`.ddev/config.yaml`: PHP 8.4, MariaDB 10.11). The functional tests use the
database named in the `typo3Database*` variables of the DDEV config. The
private repository `cpsit/cps-utility` needs your SSH key in the container:
run `ddev auth ssh` once. There is no CI pipeline in this repository.

```
ddev start
ddev auth ssh
ddev composer install
```

### Unit Tests

```
ddev composer test:unit
```

### Functional Tests

```
ddev composer test:functional
```

### All Tests

```
ddev composer test
```
