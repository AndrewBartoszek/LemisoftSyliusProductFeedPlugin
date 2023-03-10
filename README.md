# Szablon wtyczki do sylius e-commerce

## Wymagania

- [PHP](https://www.php.net) w wersji min 8.2
- [MySQL](https://www.mysql.com) w wersji min 8.0
- [Composer](https://getcomposer.org) w wersji min 2.5.1

Dla php należy włączyć następujące rozszerzenia:

- pdo_mysql

1. Kod PHP'owy musi spełniać standard formatowania kodu [PSR-12](https://www.php-fig.org/psr/psr-12/).
2. Używamy najnowszych funkcjonalności języka PHP
3. Używanie klas final
4. Do wszystkiego należy pisać testy (phpunit, behat, phpspec)
5. Najlepiej używać plików konfiguracyjnych w formacie **.php**
6. Publikacja wtyczki oraz używanie semantic version

## Instalacja pluginu
- Tworzymy plik:
   ```bash
   #src/Entity/ProductFeed/ProductFeed.php

   declare(strict_types=1);

   namespace App\Entity\ProductFeed;

   use \Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeed as BaseProductFeed;

   class ProductFeed extends BaseProductFeed
   {

   }
   ```

- W pliku z repozytorium dla encji Product - zwykle plik src/Repository/ProductRepository.php użyć traita:
   ```bash
   #src/Repository/ProductRepository.php

    declare(strict_types=1);

    namespace Lemisoft\Tests\SyliusProductFeedsPlugin\Application\src\Doctrine\Orm;

    use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductRepositoryInterface;
    use Lemisoft\SyliusProductFeedsPlugin\Repository\ProductRepositoryTrait;
    use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

    class ProductRepository extends BaseProductRepository  implements ProductRepositoryInterface
    {
        use ProductRepositoryTrait;
    }
   ```

- Dodajemy doctrinowe typy:
  ```bash
  #config/packages/doctrine.yaml

    doctrine:
        dbal:
            types:
                feed_type: Lemisoft\SyliusProductFeedsPlugin\Resources\config\doctrine\enum\FeedTypeEnumType
                feed_state: Lemisoft\SyliusProductFeedsPlugin\Resources\config\doctrine\enum\FeedStateEnumType
                product_name_mode: Lemisoft\SyliusProductFeedsPlugin\Resources\config\doctrine\enum\ProductNameModeType
  ```

- Do .env dodać zmienną z rodzajami feed-ów, które mają być dostępne w aplikacji:
   ```bash
   #.env

   PRODUCT_FEED_AVAILABLE='["ceneo", "facebook", "google"]'
   ```
- W pliku config/services.yaml dodać import:
   ```bash
   #config/services.yaml

   imports:
   - { resource: "@LemisoftSyliusProductFeedsPlugin/src/Resources/config/app/config.php" }
   ```
- W pliku config/routes.yaml dodać import:
   ```bash
   lemisoft_sylius_product_feeds_plugin_shop:
       resource: "@LemisoftSyliusProductFeedsPlugin/config/shop_routing.yml"
       prefix: /{_locale}
       requirements:
           _locale: ^[a-z]{2}(?:_[A-Z]{2})?$

   lemisoft_sylius_product_feeds_plugin_admin:
       resource: "@LemisoftSyliusProductFeedsPlugin/config/admin_routing.yml"
       prefix: /admin
   ```

## Uruchomienie wtyczki

Wtyczka uruchamiana jest przy użyciu Docker.
Po sklonowaniu projektu należy zmienić nazewnictwo klas oraz plików konfiguracyjnych zgodnie z [dokumentacją](https://docs.sylius.com/en/latest/book/plugins/guide/naming.html).

## Publikacja w Package Registry

Każda wtyczka powinna zostać opublikowana w package registry zgodnie z numeracją semantic version. Proces publikacji wykonywany jest w gitlab ci/cd przy tagowaniu pakietu lub wywołana ręcznie.

### Użycie wtyczki

Instrukcja instalacji dostępna jest pod adresem https://gitlab.lemisoft.pl/help/user/packages/composer_repository/index#install-a-composer-package

1. Dodać package registry url w pliku composer.json
   ```bash
    composer config repositories.gitlab.lemisoft.pl/105 '{"type": "composer", "url": "https://gitlab.lemisoft.pl/api/v4/group/105/-/packages/composer/packages.json"}
   ```
2. Wygenerować plik auth.json:
   ```bash
   composer config gitlab-token.gitlab.lemisoft.pl package_registry n52_REGt4a3cGfVZC_im
   ```

3. Dodać sekcje gitlab-domain w composer.json
   ```bash
   composer config gitlab-domains gitlab.lemisoft.pl
   ```
4. Zainstalować pakiet

### Docker

Do uruchomienia wtyczki potrzebujemy lokalnie zainstalowanych narzędzi:

* [Docker](https://www.docker.com/get-started)
* [Docker Compose](https://docs.docker.com/compose/install/)

W projekcie zostały zdefiniowane następujące kontenery:

* `php`
* `mysql`
* `ngnix`
* `node`

Aby uruchomić projekt, należy:

1. Podczas pierwszego uruchomienia należy się zalogować w naszym gitlab:

    ```bash
   docker login gitlab.lemisoft.pl:5050
    ```

2. Uruchomić kontenery
    ```bash
    docker compose up -d
    ```

3. Inicjalizacja wtyczki
    ```bash
   docker compose exec app make init
    ```

Po odpowiednim skonfigurowaniu i uruchomieniu kontenerów aplikacja dostępna jest pod adresem: **localhost**

## Dokumentacja Sylius'a

Dokumentacja dostępna jest pod adresem [plugin.sylius.com](https://docs.sylius.com/en/latest/book/plugins/guide/index.html).

## Jakość kodu

### Eslint

Statyczna analiza kodu. Konfiguracja znajduje się w pliku: *[.eslintrc.js](.eslintrc.js)*

```bash
make eslint
```

### PHPStan

Statyczna analiza kodu. Konfiguracja znajduje się w pliku: *[phpstan.neon](phpstan.neon)*

```bash
make phpstan
```

### PHP Code Sniffer

Statyczna analiza kodu. Konfiguracja znajduje się w pliku: *[phpcs.xml.dist](phpcs.xml.dist)*

```bash
make phpcs
```

### PHP ECS

Statyczna analiza kodu. Konfiguracja znajduje się w pliku: *[ecs.php](ecs.php)*

```bash
make ecs
```

### Php Magic Number Detector

Wykrywanie magicznych liczb.

```bash
make phpmnd
```

## Testy

### PhpUnit

Plik konfiguracyjny: *[phpunit.xml.dist](phpunit.xml.dist)*

```bash
make phpunit
```

### Behat

Plik konfiguracyjny: *[behat.yml.dist](behat.yml.dist)*

```bash
make behat
```

### PhpSpec

Plik konfiguracyjny: *[phpspec.yaml.dist](phpspec.yml.dist)*

```bash
make phpspec
```
