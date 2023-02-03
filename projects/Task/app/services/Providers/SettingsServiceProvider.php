<?php
namespace App\Services\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use App\Services\Settings\SettingContainer;
use App\Services\Settings\SettingGroup;
use App\Services\Settings\SettingValue;

/**
 * Class ServiceProvider
 * Service provider for settings.
 * @package App\Service\Settings
 */
class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'App\Services\Settings\SettingContainer',
            function () {
                $settingContainer = new SettingContainer;

                $common = new SettingGroup('Общие');
                $settingContainer->addSettingGroup($common);

                $common->addSettingValue(
                    new SettingValue(
                        'phone_numbers.0',
                        'Телефонный номер',
                        '+7 (495) 151-83-75'
                    )
                );
                $common->addSettingValue(
                    new SettingValue(
                        'footer_address',
                        'Адрес в футере',
                        'г. Москва, ул. Марксистская, д. 20'
                    )
                );

                $common->addSettingValue(
                    new SettingValue(
                        'header_footer_email',
                        'E-mail в шапке и футере',
                        'lit-mebel@mail.ru'
                    )
                );

                $common->addSettingValue(
                    new SettingValue(
                        'sidebar_page',
                        'Сайдбар в разделах',
                        '',
                        'Во всех разделах, где сайдбар не задан выводится данный блок',
                        SettingValue::TYPE_TEXTAREA
                    )
                );

                $common->addSettingValue(
                    new SettingValue(
                        'block_links_footer',
                        'Блок ссылок в футере',
                        '',
                        '',
                        SettingValue::TYPE_TEXTAREA
                    )
                );

                $common->addSettingValue(
                    new SettingValue(
                        'sidebar_stati',
                        'Сайдбар в статьях',
                        '',
                        '',
                        SettingValue::TYPE_TEXTAREA
                    )
                );


                $additionalLinkGroup = new SettingGroup('Дополнительные ссылки');
                $settingContainer->addSettingGroup($additionalLinkGroup);
                $additionalLinkGroup->addSettingValue(
                    new SettingValue(
                        'link.delivery',
                        'Ссылка на страницу "Доставка"',
                        '#'
                    )
                );
                $additionalLinkGroup->addSettingValue(
                    new SettingValue(
                        'link.payment',
                        'Ссылка на страницу "Оплата"',
                        '#'
                    )
                );
                $additionalLinkGroup->addSettingValue(
                    new SettingValue(
                        'link.guarantee',
                        'Ссылка на страницу "Гарантия"',
                        '#'
                    )
                );

                $additionalLinkGroup->addSettingValue(
                    new SettingValue(
                        'link.disclaimer',
                        'Ссылка на страницу "Ограничение ответственности"',
                        '/disclaimer'
                    )
                );


                $admin = new SettingGroup('Система администрирования');
                $settingContainer->addSettingGroup($admin);

                $admin->addSettingValue(
                    new SettingValue(
                        'admin.field_descriptions',
                        'Описания полей',
                        '',
                        '<div style="text-align: left; display: inline-block;">
                        <strong>Пример</strong>:<br />
                        <strong>Название:</strong> "описание названия"<br />
                        <strong>"Краткое содержимое":</strong> "Описание краткого содержимого"
                        </div>',
                        SettingValue::TYPE_TEXTAREA
                    )
                );

                $notifications = new SettingGroup('Уведомления');
                $settingContainer->addSettingGroup($notifications);

                $notifications->addSettingValue(
                    new SettingValue(
                        'mail.from.address',
                        'Е-mail отправителя (от кого)',
                        '',
                        'Если поле не заполнено, то используется почта <i>noreply@{название сайта}</i>.<br>
                        <div style="color:#af294d">должен быть только один email<div>',
                        SettingValue::TYPE_TEXT
                    )
                );

                $notifications->addSettingValue(
                    new SettingValue(
                        'mail.reply_to.address',
                        'Адрес для ответа в письмах посетителям сайта',
                        'lit-mebel@mail.ru',
                        'Если пользователь нажмет на кнопку "Ответить на письмо", ответ будет отправлен на этот адрес.<br>
                        <div style="color:#af294d">должен быть только один email<div>',
                        SettingValue::TYPE_TEXT
                    )
                );

                $notifications->addSettingValue(
                    new SettingValue(
                        'mail.from.name',
                        'Имя в обратном e-mail в письмах',
                        'lit-mebel.ru'
                    )
                );

                $notifications->addSettingValue(
                    new SettingValue(
                        'mail.feedback.address',
                        'Email обратной связи',
                        'lit-mebel@mail.ru'
                    )
                );

                $notifications->addSettingValue(
                    new SettingValue(
                        'mail.reviews.address',
                        'E-mail для уведомлений из раздела "Отзывы"',
                        'lit-mebel@mail.ru'
                    )
                );


                $texts = new SettingGroup('Текстовые константы');
                $settingContainer->addSettingGroup($texts);
                $texts->addSettingValue(
                    new SettingValue(
                        'texts.product.preorder',
                        'Блок-предупреждение о индивидуальном заказе',
                        '',
                        'Пример: У нас Вы можете заказать такую же модель по своим размерам с учетом всех ваших пожеланий. Доступны различные цвета, варианты отделки, дополнительные элементы и фурнитура',
                        SettingValue::TYPE_TEXTAREA
                    )
                );

                $texts->addSettingValue(
                    new SettingValue(
                        'texts.form.wantthesame',
                        'Дополнительный текст после заголовка на форме "Сделать заказ"',
                        'Мы можем изготовить похожий шкаф с учётом любых Ваших пожеланий.<br />
Оставьте свои контактные данные и в ближайшее время наш дизайнер свяжется с Вами и обсудит все детали.<br />
Также воспользуйтесь этой формой, если Вас интересует стоимость изготовления.',
                        'Пример: Мы можем изготовить похожий шкаф с учётом любых Ваших пожеланий.<br />
Оставьте свои контактные данные и в ближайшее время наш дизайнер свяжется с Вами и обсудит все детали.<br />
Также воспользуйтесь этой формой, если Вас интересует стоимость изготовления.',
                        SettingValue::TYPE_TEXTAREA
                    )
                );

                $texts->addSettingValue(
                        new SettingValue(
                                'texts.form.callbacks',
                                'Дополнительный текст после заголовка на форме "Заказать обратный звонок"',
                                'Мы можем изготовить похожий шкаф с учётом любых Ваших пожеланий.<br />
Оставьте свои контактные данные и в ближайшее время наш дизайнер свяжется с Вами и обсудит все детали.<br />
Также воспользуйтесь этой формой, если Вас интересует стоимость изготовления.',
                                'Пример: Мы можем изготовить похожий шкаф с учётом любых Ваших пожеланий.<br />
Оставьте свои контактные данные и в ближайшее время наш дизайнер свяжется с Вами и обсудит все детали.<br />
Также воспользуйтесь этой формой, если Вас интересует стоимость изготовления.',
                                SettingValue::TYPE_TEXTAREA
                        )
                );

                $redirects = new SettingGroup('Редиректы');
                $settingContainer->addSettingGroup($redirects);

                $redirects->addSettingValue(
                        new SettingValue(
                                'redirects.rules',
                                'Правила редиректов',
                                '',
                                '<div style="text-align: left; display: inline-block;">
<i><strong>Формат описания правил</strong>: <br />
{правило} - регулярное выражение<br />
{ссылка} - целевая ссылка <br />
</i><br />
<strong>Пример правила редиректа</strong>:<br />
<i><br />
<span style="font-size: 14px;">^/catalog(/.*)?$&nbsp;&nbsp;&nbsp;<strong>></strong>&nbsp;&nbsp;&nbsp;/</span><br />
</i><br />
где<br />
^   - начало текста<br />
$   - конец текста<br />
()? - необязательная часть текста<br />
.*  - любой текст<br />
<br />
Данное правило соотвествует следующим адересам:<br />
/catalog<br />
/catalog/<br />
/catalog/category-1<br />
/catalog/category-2<br />
                        </div>',
                                SettingValue::TYPE_REDIRECTS
                        )
                );


                $calc = new SettingGroup('Блок "Калькулятор шкафа"');
                $settingContainer->addSettingGroup($calc);
                $calc->addSettingValue(
                        new SettingValue(
                                'calc.turnOn',
                                'Включить отображение плавающей кнопки-ссылки',
                                1,
                                '',
                                SettingValue::TYPE_CHECKBOX
                        )
                );
                $calc->addSettingValue(
                        new SettingValue(
                                'calc.link',
                                'Ссылка на страницу калькулятора',
                                'http://www.lit-mebel.ru/constructor',
                                'Пример: http://www.lit-mebel.ru/constructor',
                                SettingValue::TYPE_TEXT
                        )
                );

                $banner = new SettingGroup('Баннер в фиксированной шапке');
                $settingContainer->addSettingGroup($banner);
                $banner->addSettingValue(
                        new SettingValue(
                                'banner.turnOn',
                                'Отображать баннер в фиксированной шапке',
                                1,
                                '',
                                SettingValue::TYPE_CHECKBOX
                        )
                );
                $banner->addSettingValue(
                        new SettingValue(
                                'banner.onlyLink',
                                'Отображать баннер только для выбранной странице',
                                1,
                                '',
                                SettingValue::TYPE_CHECKBOX
                        )
                );
                $banner->addSettingValue(
                        new SettingValue(
                                'banner.url',
                                'Отображать баннер только на этой страницe',
                                '/type/shkafy-v-tualet',
                                'Пример: /type/shkafy-v-tualet',
                                SettingValue::TYPE_TEXT
                        )
                );
                $banner->addSettingValue(
                        new SettingValue(
                                'banner.link',
                                'Ссылка баннера',
                                'http://www.lit-mebel.ru/cat/shkafy-kupe',
                                'Пример: http://www.lit-mebel.ru/cat/shkafy-kupe',
                                SettingValue::TYPE_TEXT
                        )
                );


                return $settingContainer;
            }
        );

        $this->app->bindShared(
            'setting_getter',
            function () {
                return $this->app->make('App\Services\Settings\SettingGetter');
            }
        );
    }
}
