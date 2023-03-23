# FAQ

Frequently Asked Questions. This package is an extension to the TYPO3 system.

### Installation

* This is a private package. Please add the repo url to your root composer json first.

```
composer config repositories.t3faq vcs git@git.dena.de:common/components/t3faq.git
```

```
composer require fr/t3faq 
```

## Actions / Views

### List selected FAQs

Displays a list of selected FAQs items in frontend. If no, FAQs item ist selected nothing will be displayed.

**Action**: listSelectedAction

**Cache**: Cacheable

### List FAQs

Displays a list of  FAQs items in frontend sorted by weight. Editors are able to filter the results by category. 
Editors have the possibility to enable or disable the search form in front end. **Important**: _Search and filters have to be done with Ajax_.

**Action**: listSelectedAction

**Cache**: Cacheable

## Tests

### Setup Test Environment 

```
docker-composer -f Tests/Build/docker-compose.yml up -d
docker-compose -f Tests/Build/docker-compose.yml exec web bash -c "cd /app && composer test:functional:prepare"
```

### Unit Tests

```
docker-compose -f Tests/Build/docker-compose.yml exec web bash -c "cd /app && composer test:unit"
```

### Functional Tests

```
docker-compose -f Tests/Build/docker-compose.yml exec web bash -c "cd /app && composer test:functional:run"
```
