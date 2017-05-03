<p align="center"><img src="http://placehold.it/350x150" height="150px"></p>

<p align="center">
<a href="https://travis-ci.org/komcommy/HAN-G42-EenmaalAndermaal"><img src="https://img.shields.io/travis/rust-lang/rust.svg" alt="Build"></a>
<a href="#"><img src="https://img.shields.io/badge/status-development-yellow.svg" alt="Status"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/github/license/mashape/apistatus.svg" alt="MIT License"></a>
</p>

## About EenmaalAndermaal

About eenmaalandermaal shizzle

## Contribute

How to contribute?

1. Fork it
2. Create your feature branch: 
    ```
    git checkout -b my-new-feature
    ```
3. Commit your changes: 
    ```
    git commit -am 'Add some feature'
    ```
4. Push to the branch: 
    ```
    git push origin my-new-feature
    ```
5. Submit a pull request

## Privacy and security

If you discover a security vulnerability within the EenmaalAndermaal-code, please send an e-mail to support@ea-veiling.nl. All security vulnerabilities will be promptly addressed.

## License

Closed source sumting - add later !!



-----------

## Installation (through Command Line Interface)

1. Clone the Git repository:

    ```
    git clone https://github.com/komcommy/HAN-G42-EenmaalAndermaal.git
    ```

2. Create a database in `MySQL`:

    ```
    Host: localhost
    Database: 4dkm_db
    Username: 4dkm_user
    Password: p@ssw0rd
    ```

3. Create database migration:

    ```
    php artisan migrate
    ```
    
4. Create useraccount and grand admin rights:

    Go to http://localhost/register
    After registration, change database values for your account
    ```
    Function: "beheerder"
    Admin: "1"
    ```
    This feature will soon be integrated within the code to simplify actions. But for now, change the database data to become an awesome and sexy admin ;-).

## Updating

Automated updates are not supported yet, follow the documentation when upgrading!

## Usage

Once installed, visit the homepage on http://localhost.
If you have any issues, run the following command in the Command Line Interface:
    ```
    composer update
    ```

Views / pages are found in:
```    
    /resources
    /resources/views
    /resources/IT (CRUD IT manager)
    /resources/admin (admin pages)
    /resources/auth (login and registration pages)
    /resources/clients (CRUD client manager)
    /resources/debug (only for debugging purposes)
    /resources/mealmanager (all mealmanager pages)
    /resources/pages (generic pages)
```

## Configuration

(can we create an .env file for this?)