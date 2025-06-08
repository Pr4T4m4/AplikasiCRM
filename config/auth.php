<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web', // Guard default untuk aplikasi (biasanya untuk pengguna biasa)
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great starting point is the "web" guard which uses
    | session storage and the Eloquent user provider.
    |
    | All authentication drivers have a provider option, which defines how a user
    | is actually retrieved out of your database or other storage mechanisms.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users', // Menggunakan provider 'users'
        ],

        'admin' => [ // <<< INI ADALAH GUARD BARU UNTUK ADMIN
            'driver' => 'session',
            'provider' => 'admins', // Menggunakan provider 'admins' yang akan kita definisikan di bawah
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms to authenticate them.
    |
    | If you have multiple user tables or models, you may configure multiple
    | sources to authenticate against. You may even configure several
    | providers to work with at the same time.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Model untuk pengguna biasa
        ],

        'admins' => [ // <<< INI ADALAH PROVIDER BARU UNTUK ADMIN
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // Model untuk admin
        ],

        // 'ldap' => [
        //     'driver' => 'ldap',
        //     'model' => App\Models\User::class,
        //     'rules' => [],
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify how the password reset functionality of your application
    | works. This gives you room to specify the table views might utilize for
    | a password reset token. You may even set a user provider to override
    | the default.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens', // Ini untuk pengguna biasa
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [ // <<< INI ADALAH KONFIGURASI RESET PASSWORD UNTUK ADMIN
            'provider' => 'admins',
            'table' => 'password_reset_tokens', // Bisa menggunakan tabel yang sama atau terpisah
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds that lasts the password
    | confirmation timeout before the user is prompted to re-enter their
    | password. This security feature keeps your users safely protected.
    |
    */

    'password_timeout' => 10800, // 3 hours

];