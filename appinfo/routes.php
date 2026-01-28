<?php

declare(strict_types=1);

return [
    'routes' => [
        // Frontend
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],

        // Accounts
        ['name' => 'account_api#index', 'url' => '/accounts', 'verb' => 'GET'],
        ['name' => 'account_api#create', 'url' => '/accounts', 'verb' => 'POST'],
        ['name' => 'account_api#show', 'url' => '/accounts/{id}', 'verb' => 'GET'],
        ['name' => 'account_api#update', 'url' => '/accounts/{id}', 'verb' => 'PUT'],
        ['name' => 'account_api#destroy', 'url' => '/accounts/{id}', 'verb' => 'DELETE'],

        // Categories
        ['name' => 'category_api#index', 'url' => '/categories', 'verb' => 'GET'],
        ['name' => 'category_api#create', 'url' => '/categories', 'verb' => 'POST'],
        ['name' => 'category_api#show', 'url' => '/categories/{id}', 'verb' => 'GET'],
        ['name' => 'category_api#update', 'url' => '/categories/{id}', 'verb' => 'PUT'],
        ['name' => 'category_api#destroy', 'url' => '/categories/{id}', 'verb' => 'DELETE'],

        // Transactions
        ['name' => 'transaction_api#index', 'url' => '/transactions', 'verb' => 'GET'],
        ['name' => 'transaction_api#create', 'url' => '/transactions', 'verb' => 'POST'],
        ['name' => 'transaction_api#show', 'url' => '/transactions/{id}', 'verb' => 'GET'],
        ['name' => 'transaction_api#update', 'url' => '/transactions/{id}', 'verb' => 'PUT'],
        ['name' => 'transaction_api#destroy', 'url' => '/transactions/{id}', 'verb' => 'DELETE'],

        // SEPA
        ['name' => 'sepa_api#export', 'url' => '/sepa/export', 'verb' => 'POST'],
    ],
];
