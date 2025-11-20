<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Messages
    |--------------------------------------------------------------------------
    |
    | Override default validation messages
    |
    */

    'messages' => [
        'required' => 'The :attribute field is required.',
        'email' => 'The :attribute must be a valid email address.',
        'min' => 'The :attribute must be at least :min characters.',
        'max' => 'The :attribute may not be greater than :max characters.',
        'numeric' => 'The :attribute must be a number.',
        'integer' => 'The :attribute must be an integer.',
        'string' => 'The :attribute must be a string.',
        'url' => 'The :attribute must be a valid URL.',
        'confirmed' => 'The :attribute confirmation does not match.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Attribute Names
    |--------------------------------------------------------------------------
    |
    | Map field names to human-readable labels
    |
    */

    'attributes' => [
        'email' => 'email address',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
    ],
];
