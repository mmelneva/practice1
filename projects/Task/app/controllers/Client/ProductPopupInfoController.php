<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\ProductTypePageAssociation\ProdTypePageAssociationRepoInterface;

class ProductPopupInfoController extends BaseController
{
    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

    /**
     * @var ProdTypePageAssociationRepoInterface
     */
    private $prodTypePageAssociationRepository;

    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepository
     * @param NodeRepositoryInterface $nodeRepository
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepository,
        NodeRepositoryInterface $nodeRepository
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->prodTypePageAssociationRepository = $prodTypePageAssociationRepository;
        $this->nodeRepository = $nodeRepository;
    }

    public function getProductPopupInfoBlock()
    {
        $status = 'ERROR';
        $content = '';
        if (\Request::ajax()) {
            $productId = \Input::get('product_id');
            $target = \Input::get('target', false);
            $productTypePageNodeId = \Input::get('page_id');
            if (!is_null($productId)) {
                $product = $this->catalogProductRepository->findPublishedById($productId);
                if (!is_null($product)) {
                    if (!empty($productTypePageNodeId)) {
                        $productAdditionalInfo = $this->getAdditionalInfo($productTypePageNodeId, $productId);
                    } else {
                        $productAdditionalInfo = [];
                    }

                    $status = 'OK';
                    $content = \View::make('client.layouts._product_popup._product_info')
                        ->with('show_small_content', ($target == 'list') )
                        ->with(compact('product', 'productAdditionalInfo'))
                        ->render();
                }
            }
        }

        return \Response::json(compact('status', 'content'));
    }

    private function getAdditionalInfo($productTypePageNodeId, $productId)
    {
        $productTypePageNode = $this->nodeRepository->findById($productTypePageNodeId, true);

        if (!is_null($productTypePageNode) &&
            $productTypePageNode->type == StructureTypesServiceProvider::TYPE_PRODUCT_TYPE_PAGE
        ) {
            $productTypePage = $productTypePageNode->productTypePage;
            if (!is_null($productTypePage)) {
                return $this->getAdditionalInfoForProduct($productTypePage->id, $productId);
            }
        }

        return [];
    }

    private function getAdditionalInfoForProduct($productTypePageId, $productId)
    {
        $productAssociation = $this->prodTypePageAssociationRepository->getAssociationForPageAndProduct(
            $productTypePageId,
            $productId
        );

        $prodAdditionalInfo = [];
        if (!is_null($productAssociation) && !empty($productAssociation->name)) {
            $prodAdditionalInfo['name'] = $productAssociation->name;
        }

        return $prodAdditionalInfo;
    }
}
