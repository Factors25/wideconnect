# WIDECONNECT

Backoffice application for **WIDECONNECT**

## ▶ Versions

- PHP 8.2.5 https://www.php.net/downloads
- NodeJS 18.12.0 https://nodejs.org/en/download
- Yarn 1.22.19 https://yarnpkg.com/
- Composer 2 https://getcomposer.org/download/
- Symfony 6.4.* https://symfony.com/download

## 📘 Utils (Recommended for new users)

- https://www.wampserver.com/
- https://www.apachefriends.org/
- https://www.mamp.info/

## 🚀 Getting started

- Clone this project with access token or sshkey
    - `git clone https://gitlab.com/Factors25/WIDECONNECT.git`
- Copy .env to .env.local and configure it
    - `DATABASE_URL="mysql://root:@127.0.0.1:3306/WIDECONNECT"`
- Install dependencies
    - `composer install`
    - `yarn install`

## 💻 Commands

### Symfony

- Clear cache
    - `php bin/console c:c` (php bin/console cache:clear)
- Database
    - Create
        - `php bin/console d:d:c` (php bin/console doctrine:database:create)
    - Delete
        - `php bin/console d:d:d -f` (php bin/console doctrine:database:drop --force)
- Migrations
    - Generate a migration
        - `php bin/console d:m:diff` (php bin/console doctrine:migrations:diff)
    - Migrate all migrations
        - `php bin/console d:m:m` (php bin/console doctrine:migrations:migrate)
- Fixtures
    - `php bin/console d:f:l` (php bin/console doctrine:fixtures:load)
- Server php
    - Start
        - `symfony serve` (symfony server:start)
    - Stop
        - `symfony server:stop`

### Composer

- Add dependencies
    - `composer require <package-name>`
- Remove dependencies
    - `composer remove <package-name>`

### Yarn

- Server
    - Start
        - `yarn dev --watch`
    - Build prod
        - `yarn build`
- Add dependencies
    - `yarn add <package-name>`
- Remove dependencies
    - `yarn remove <package-name>`
