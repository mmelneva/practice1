<?php

return [
    'auto_inject' => true,
    'rules' => [
        [
            'selector' => '#logotype',
            'title' => 'Это логотип.',
        ],
        [
            'selector' => '.price',
            'title' => 'Это цена',
            'position' => ['left', 'top'],
        ],
    ],
];
