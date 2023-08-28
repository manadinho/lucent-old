<?php

return [
    'can_edit_team' => [
        'owner' => true,
        'admin' => true,
        'user' => false,
    ],
    'can_delete_team' => [
        'owner' => true,
        'admin' => false,
        'user' => false,
    ],
    'can_create_project' => [
        'owner' => true,
        'admin' => true,
        'user' => false,
    ],
    'can_edit_project' => [
        'owner' => true,
        'admin' => true,
        'user' => false,
    ],
    'can_delete_project' => [
        'owner' => true,
        'admin' => false,
        'user' => false,
    ],
    'can_add_member' => [
        'owner' => true,
        'admin' => true,
        'user' => false,
    ],
    'can_remove_member' => [
        'owner' => true,
        'admin' => true,
        'user' => false,
    ],
    'can_generate_key' => [
        'owner' => true,
        'admin' => false,
        'user' => false,
    ],
];
