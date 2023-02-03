<?php namespace App\Services\Seo;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Models\ProductTypePage;
use App\Services\Pagination\Pagination;

/**
 * Class MetaHelper
 * @package App\Services\Seo
 */
class MetaHelper
{
    private $metaTemplates;

    private $title_product = [
        '{Name} на заказ по индивидуальным размерам, купить {name} в Москве',
        '{Name} на заказ в Москве, купить {name} по индивидуальным размерам недорого',
        '{Name}, заказать по индивидуальным размерам, купить {name} в Москве с доставкой'
    ];

    private $description_product = [
        'В интернет-магазине Лит Мебель Вы сможете заказать {Name} по индивидуальным размерам. Мы доставляем изделия по Москве и области! Низкие цены!',
        'У нас Вы сможете купить готовые шкафы или заказать {Name} по своим размерам. Быстрая доставка по Москве и Области. Заказывайте по телефону или на сайте!',
        'В нашем каталоге Вы сможете купить {Name} или заказать его по индивидуальным размерам! Отличное качество, доступные цены! Доставка по Москве и Области'
    ];

    private $title_category = [
        '{Name} на заказ по своим размерам, заказать в Москве недорого',
        '{Name} на заказ, купить {name} недорого по своим размерам',
        '{Name}, купить {name} в Москве с доставкой по индивидуальным размерам'
    ];

    private $description_category = [
        'В нашем магазине ЛитМебель Вы сможете заказать {Name} по своим размерам. Мы доставляем изделия по Москве и области! Доступные цены!',
        'У нас можно приобрести как  готовые шкафы, так и заказать {Name} по своим размерам. Быстрая доставка по Москве. Звоните и заказывайте!',
        'В нашем каталоге можно купить {Name} или заказать его изготовление по своим размерам! Отличное качество, доступные цены!'
    ];

    private $title_subcategory = [
        '{Name} по своим размерам, купить {name} в Москве готовый или под заказ',
        '{Name} недорого под заказ, купить {name} по индивидуальным размерам ',
        '{Name}, заказать по своим размерам, купить в Москве с доставкой'
    ];

    private $description_subcategory = [
        'У нас Вы сможете заказать {Name} по своим размерам. Мы доставляем изделия по Москве и области! Низкие цены! Пишите, звоните.',
        'Мы предлагаем купить уже готовые шкафы или заказать {Name} по своим размерам. Наши преимущества это: отличное качество шкафов и доступные цены!',
        'В нашем каталоге Вы сможете купить {Name}, либо заказать его по своим индивидуальным размерам!  Доступные цены и качественные шкафы!'
    ];

    /**
     * Set meta templates.
     *
     * @param $object
     * @return array
     */
    public function setMetaTemplates($object)
    {
        $defaultMeta = [
            'meta_title' => '{name}',
            'meta_description' => '{Name}. Компания ' . \Config::get('settings.company_name_ucfirst') .
                ' - это качественная мебель на заказ: быстрое изготовление, доставка, сборка, гарантия от производителя.',
            'meta_keywords' => '{name}, ' . \Config::get('settings.company_name') . ', мебель на заказ',
        ];

        if ($object == null) {
            $metaTemplateGroups = ['default' => $defaultMeta];
        } else {
            $metaTemplateGroups = [
                'catalog_page' => [
                    'meta_title' => $this->getMetaTemplate($object, $this->title_category),
                    'meta_description' => $this->getMetaTemplate($object, $this->description_category),
                    'meta_keywords' => '{name}, на заказ, цены, купить, ' .
                        \Config::get('settings.company_name'),
                ],
                'product_page' => [
                    'meta_title' => $this->getMetaTemplate($object, $this->title_product),
                    'meta_description' => $this->getMetaTemplate($object, $this->description_product),
                    'meta_keywords' => '{name}, на заказ, цена, купить, ' .
                        \Config::get('settings.company_name'),
                ],
                'default' => $defaultMeta,
            ];

            if ($object instanceof CatalogCategory) {
                if ($object->id == '4' || $object->id == '5') {
                    $metaTemplateGroups['catalog_page'] = [
                        'meta_title' => $this->getMetaTemplate($object, $this->title_subcategory),
                        'meta_description' => $this->getMetaTemplate($object, $this->description_subcategory),
                        'meta_keywords' => '{name}, на заказ, цены, купить, ' .
                            \Config::get('settings.company_name'),
                    ];
                }
            }
        }
        return $metaTemplateGroups;
    }

    private function getMetaTemplate($object, array $type_meta)
    {
        return $type_meta[$object->id % count($type_meta)];
    }

    /**
     * Get meta templates by key.
     *
     * @param $key
     * @param $object
     * @return mixed
     */
    private function getMetaTemplates($key, $object)
    {
        return array_get($this->setMetaTemplates($object), $key, $this->metaTemplates['default']);
    }

    /**
     * Get meta for some object, probably, with name, maybe header and so on.
     *
     * @param $object
     * @param null $name
     * @return array
     */
    public function metaForObject($object, $name = null)
    {
        $pageName = !is_null($name) ? $name : $object->name;

        if (!empty($object->header)) {
            $h1 = $object->header;
        } else {
            $h1 = null;
        }

        if (!empty($object->meta_title)) {
            $metaTitle = $object->meta_title;
        } else {
            $metaTitle = null;
        }

        if (!empty($object->meta_keywords)) {
            $metaKeywords = $object->meta_keywords;
        } else {
            $metaKeywords = null;
        }

        if (!empty($object->meta_description)) {
            $metaDescription = $object->meta_description;
        } else {
            $metaDescription = null;
        }

        return $this->prepareMeta(
            [
                'h1' => $h1,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'meta_keywords' => $metaKeywords
            ],
            $pageName,
            $this->templateKeyFor($object),
            $object
        );
    }

    /**
     * Get meta info just for name.
     *
     * @param $name
     * @param string $templateKey
     * @return array
     */
    public function metaForName($name, $templateKey = 'default')
    {
        return $this->prepareMeta([], $name, $templateKey);
    }

    /**
     * Apply default rules for meta data.
     *
     * @param array $metaData
     * @param null $name
     * @param string $templateKey
     * @param null $object
     * @return array
     */
    private function prepareMeta(array $metaData = [], $name = null, $templateKey = 'default', $object = null)
    {
        $metaTemplates = $this->getMetaTemplates($templateKey, $object);

        if (empty($metaData['h1'])) {
            if (!empty($name)) {
                $metaData['h1'] = $name;
            } else {
                $metaData['h1'] = '';
            }
        }

        if (empty($metaData['meta_title'])) {
            $metaData['meta_title'] = mb_ucfirst(
                str_replace(
                    ['{name}', '{Name}'],
                    [mb_lcfirst($name), mb_ucfirst($name)],
                    $metaTemplates['meta_title']
                )
            );
        }

        if (empty($metaData['meta_keywords'])) {
            $metaData['meta_keywords'] = str_replace(
                ['{name}', '{Name}'],
                [$name, $name],
                $metaTemplates['meta_keywords']
            );
        }
        $metaData['meta_keywords'] = mb_strtolower($metaData['meta_keywords']);

        if (empty($metaData['meta_description'])) {
            $metaData['meta_description'] = mb_ucfirst(
                str_replace(
                    ['{name}', '{Name}'],
                    [mb_lcfirst($name), mb_ucfirst($name)],
                    $metaTemplates['meta_description']
                )
            );
        }

        return $metaData;
    }

    /**
     * Get template key for object.
     *
     * @param $object
     * @return string
     */
    private function templateKeyFor($object)
    {
        $templateKey = 'default';

        if ($object instanceof CatalogProduct) {
            $templateKey = 'product_page';
        } elseif ($object instanceof CatalogCategory || $object instanceof ProductTypePage) {
            $templateKey = 'catalog_page';
        }

        return $templateKey;
    }


    public function appendPagination(array $metaData, Pagination $paginator)
    {
        if ($paginator->getCurrentPage() > 1){
            $metaData['meta_title'] .= ' - страница ' . $paginator->getCurrentPage();
            $metaData['meta_description'] .= ' - страница ' . $paginator->getCurrentPage();
        }
        return $metaData;
    }
}
