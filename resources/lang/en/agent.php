<?php


return [
    'form' => [
        'title' => [
            'create' => 'Create Agent',
            'update' => 'Update Agent'
        ],
        'fields' => [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'contact' => [
                'email' => 'Contact Email',
            ],
        ],
    ],
    'table' => [
        'title' => 'All Agents',
        'columns' => [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
        ],
    ],            
];
