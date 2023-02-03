<?php namespace App\Services\Sitemap;

use App\Controllers\Client\HomePagesController;
use App\Controllers\Client\SiteMapController;
use App\Models\Node;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SitemapGeneratorHelper
 * @package  App\Services\Helpers
 */
class SitemapGenerator
{
    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var CatalogCategoryRepositoryInterface
     */
    private $catalogCategoryRepository;

    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

    /** @var bool|string */
    private $siteMapXmlDate;
    /** @var array */
    private $pageTypesExists = [];
    /** @var array */
    private $categoriesIds = [];

    const HIGH_PRIORITY = 1;
    const MIDDLE_PRIORITY = 0.6;
    const LOW_PRIORITY = 0.5;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     * @param CatalogCategoryRepositoryInterface $catalogCategoryRepository
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     */
    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository
    ) {
        $this->siteMapXmlDate = date("Y-m-d");
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;
    }

    /**
     *  Generate site map xml file.
     */
    public function generate()
    {
        $fileResource = fopen(public_path('sitemap.xml'), 'w');
        fwrite($fileResource, $this->printSiteMap());
        fclose($fileResource);
    }

    private function printSiteMap()
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0" encoding="utf-8');
        $xml->startElement('urlset');

        $xml->startAttribute('xmlns:xsi');
        $xml->text('http://www.w3.org/2001/XMLSchema-instance');
        $xml->endAttribute();

        $xml->startAttribute('xmlns');
        $xml->text('http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->endAttribute();

        $xml->startAttribute('xsi:schemaLocation');
        $xml->text(
            'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'
        );
        $xml->endAttribute();

        $nodeData = $this->nodeStructure();
        $catalogData = $this->catalogStructure();
        $productData = $this->productStructure();

        $data = array_merge($nodeData, $catalogData, $productData);

        foreach ($data as $element) {
            $alias = trim($element['loc'], '/');
            if ( $alias != \URL::to('disclaimer')) {
                if (!isset($element['lastmod'])) {
                    $element['lastmod'] = $this->siteMapXmlDate;
                }
                if (empty($element['priority'])) {
                    $element['priority'] = self::MIDDLE_PRIORITY;
                }
                $this->getElement($xml, array('url' => $element));
           }
        }
        $xml->endElement();

        return $xml->outputMemory();
    }


    /**
     * @param \XMLWriter $xml
     * @param           $data
     */
    private function getElement(\XMLWriter $xml, $data)
    {
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $key = 'key' . $key;
            }
            if (is_array($val)) {
                $xml->startElement($key);
                $this->getElement($xml, $val);
                $xml->endElement();
            } else {
                $xml->writeElement($key, $val);
            }
        }
    }

    /**
     * Get nodes data.
     */
    private function nodeStructure()
    {
        $highPriorityTypes = [
            StructureTypesServiceProvider::TYPE_HOME_PAGE,
            StructureTypesServiceProvider::TYPE_PRODUCT_TYPE_PAGE,
        ];
        $lowPriorityTypes = [];

        $nodeTree = $this->nodeRepository->getSiteMapPageTree();
        $nodesList = $this->getNodeList($nodeTree, $highPriorityTypes, $lowPriorityTypes);

        if (!in_array(StructureTypesServiceProvider::TYPE_HOME_PAGE, $this->pageTypesExists)) {
            $node = $this->nodeRepository
                ->newInstance(
                    [
                        'name' => HomePagesController::DEFAULT_PAGE_NAME,
                        'type' => StructureTypesServiceProvider::TYPE_HOME_PAGE,
                    ]
                );
            $nodesList[] = [
                'loc' => \UrlBuilder::getUrl($node),
                'priority' => self::HIGH_PRIORITY,
            ];
        }

        if (!in_array(StructureTypesServiceProvider::TYPE_MAP_PAGE, $this->pageTypesExists)) {
            $node = $this->nodeRepository
                ->newInstance(
                    [
                        'name' => SiteMapController::DEFAULT_PAGE_NAME,
                        'type' => StructureTypesServiceProvider::TYPE_MAP_PAGE,
                    ]
                );
            $nodesList[] = [
                'loc' => \UrlBuilder::getUrl($node),
                'priority' => self::MIDDLE_PRIORITY,
            ];
        }

        return $nodesList;
    }

    /**
     * Get list of nodes data.
     *
     * @param Collection $nodeTree
     * @param $highPriorityTypes
     * @param $lowPriorityTypes
     * @return array
     */
    private function getNodeList($nodeTree, $highPriorityTypes, $lowPriorityTypes)
    {
        $elementsList = [];
        if (count($nodeTree) > 0) {
            $this->pageTypesExists = array_merge($this->pageTypesExists, $nodeTree->lists('type'));
            /** @var Node $node */
            foreach ($nodeTree as $node) {
                /** @var mixed $contentModel */
                $contentModel = \TypeContainer::getContentModelFor($node);
                if (!is_null($contentModel)) {
                    if (in_array($node->type, $highPriorityTypes)) {
                        $priority = self::HIGH_PRIORITY;
                    } elseif (in_array($node->type, $lowPriorityTypes)) {
                        $priority = self::LOW_PRIORITY;
                    } else {
                        $priority = self::MIDDLE_PRIORITY;
                    }

                    /** @var Carbon $lastMod */
                    $lastMod = $contentModel->updated_at > $node->updated_at
                        ? $contentModel->updated_at : $node->updated_at;

                    $elementsList[] = [
                        'loc' => \UrlBuilder::getUrl($node),
                        'lastmod' => $lastMod->format('Y-m-d'),
                        'priority' => $priority,
                    ];
                }

                if (!empty($node->children)) {
                    $elementsList = array_merge($elementsList, $this->getNodeList($node->children, $highPriorityTypes, $lowPriorityTypes));
                }
            }
        }

        return $elementsList;
    }

    /**
     * Get catalog tree.
     * @return array
     */
    private function catalogStructure()
    {
        $categoryTree = $this->catalogCategoryRepository->getSiteMapTree();

        return $this->getCategoriesList($categoryTree);
    }

    /**
     * Get list of categories data.
     *
     * @param Collection $categoryTree
     * @return array
     */
    private function getCategoriesList($categoryTree)
    {
        $elementsList = [];
        if (count($categoryTree) > 0) {
            $this->categoriesIds = array_merge($this->categoriesIds, $categoryTree->lists('id'));
            foreach ($categoryTree as $category) {
                $elementsList[] = [
                    'loc' => \UrlBuilder::getUrl($category),
                    'lastmod' => $category->updated_at->format('Y-m-d'),
                    'priority' => self::HIGH_PRIORITY,
                ];

                if (!empty($category->children)) {
                    $elementsList = array_merge($elementsList, $this->getCategoriesList($category->children));
                }
            }
        }

        return $elementsList;
    }

    /**
     * Get products data
     * @return array
     */
    private function productStructure()
    {
        $elementsList = [];
        $products = $this->catalogProductRepository->getPublishedInCategoriesIds($this->categoriesIds);

        if (count($products) > 0) {
            foreach ($products as $product) {
                $elementsList[] = [
                    'loc' => \UrlBuilder::getUrl($product),
                    'lastmod' => $product->updated_at->format('Y-m-d'),
                    'priority' => self::LOW_PRIORITY,
                ];
            }
        }

        return $elementsList;
    }
}
