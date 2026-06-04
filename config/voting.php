<?php

return [
    'max_failed_attempts' => 3,
    'lock_minutes_base' => 30,
    'lock_multiplier' => 2,
    'minimum_age' => 18,
    'election_statuses' => [
        'draft',
        'candidates_published',
        'voting_open',
        'voting_closed',
    ],
    'election_types' => [
        'presidential',
        'parliamentary',
    ],
    'languages' => ['en', 'sw'],
    'default_language' => 'en',
];
