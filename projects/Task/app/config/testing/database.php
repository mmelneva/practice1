<?php

return [
    'connections' => [
        'mysql' => [
            'options'   => [
                PDO::ATTR_PERSISTENT => true,
            ],
        ],
    ],
];
