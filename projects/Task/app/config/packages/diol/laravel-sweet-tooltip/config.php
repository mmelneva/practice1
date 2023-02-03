<?php

return [
    'auto_inject' => false,
    'rules' => [
        [
            'selector' => '.banner-vygoda',
            'title' => 'Баннер добавлен в шаблоне.<br/>'
                    .'Настройки регулируются в админке в блоке констант "Баннер в фиксированной шапке".<br/>'
                    .'Можно вкючить/отключить баннер, настроить отображение на одной конкретной странице, а также ссылку в баннере.',
        ],
        [
            'selector' => '.userbar',
            'title' => "Для хлебных крошек существуют исключения<br/><br/>" .
                "Страница => Измененная ссылка в хлебных крошках<br/>" .
                "<strong>shkafy-kupe/vstroennye</strong> => /cat/shkafy-kupe,<br/>" .
                "<strong>shkafy-kupe/korpusnye</strong => /cat/shkafy-kupe,<br/>" .
                "<strong>raspashnye-shkafy/vstraivaemye</strong => /cat/raspashnye-shkafy,<br/>" .
                "<strong>raspashnye-shkafy/korpusnyj</strong => /cat/raspashnye-shkafy",
            'position' => ['left', 'bottom'],
        ],
    ],
];
