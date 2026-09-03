<?php

return [
    'accepted' => 'The :attribute field must be accepted.',
    'confirmed' => 'The :attribute field confirmation does not match.',
    'email' => 'The :attribute field must be a valid email address.',
    'max' => [
        'string' => 'The :attribute field must not be greater than :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute field must be at least :min characters.',
    ],
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute field must be a string.',
    'unique' => 'The :attribute has already been taken.',

    'password' => [
        'letters' => 'The :attribute must include at least one letter.',
        'mixed' => 'The :attribute must include at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must include at least one number.',
        'symbols' => 'The :attribute must include at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],

    'attributes' => [
        'name' => 'full name',
        'email' => 'email address',
        'phone' => 'phone number',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'terms' => 'terms acceptance',
    ],

    'name_invalid_chars' => 'The full name may only contain letters, spaces, apostrophes and hyphens.',
    'name_no_urls' => 'The full name cannot contain URLs or web links.',
    'name_no_spam' => 'The full name contains prohibited promotional content.',
    'captcha_required' => 'Please complete the security check.',
    'captcha_failed' => 'Security verification failed. Please try again.',
];
