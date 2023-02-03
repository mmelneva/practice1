<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HomePage;
use App\Services\DataProviders\HomePage\BannersDataProvider;
use App\Services\FormProcessors\HomePageFormProcessor;
use App\Services\Repositories\Banner\BannerRepositoryInterface;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\HomePage\HomePageRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\ProductHomePageAssociation\ProductHomePageAssociationRepoInterface;

class HomePagesController extends BaseController
{
    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var HomePageRepositoryInterface
     */
    private $homePageRepository;

    /**
     * @var HomePageFormProcessor
     */
    private $formProcessor;

    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * @var BannersDataProvider
     */
    private $bannersDataProvider;

    private $catalogCategoryRepository;

    private $productHomePageAssociationRepo;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     * @param HomePageRepositoryInterface $homePageRepository
     * @param HomePageFormProcessor $formProcessor
     * @param BannerRepositoryInterface $bannerRepository
     * @param BannersDataProvider $bannersDataProvider
     */
    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        HomePageRepositoryInterface $homePageRepository,
        HomePageFormProcessor $formProcessor,
        BannerRepositoryInterface $bannerRepository,
        BannersDataProvider $bannersDataProvider,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        ProductHomePageAssociationRepoInterface $productHomePageAssociationRepo
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->homePageRepository = $homePageRepository;
        $this->formProcessor = $formProcessor;
        $this->bannerRepository = $bannerRepository;
        $this->bannersDataProvider = $bannersDataProvider;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->productHomePageAssociationRepo = $productHomePageAssociationRepo;
    }

    public function getEdit($nodeId = null)
    {
        $homePage = $this->getHomePage($nodeId);
        $catalogTree = $this->catalogCategoryRepository->getCatalogTree();
        $associations = $this->productHomePageAssociationRepo->getAssociationsForPage($homePage->id);

        return \View::make('admin.home_pages.edit')
            ->with('homePage', $homePage)
            ->with('node', $homePage->node)
            ->with('nodeTree', $this->nodeRepository->getCollapsedTree($nodeId))
            ->with($this->bannersDataProvider->getBannersFormData($homePage, \Input::old()))
            ->with('catalogTree', $catalogTree)
            ->with('associations', $associations);
    }

    public function putUpdate($nodeId = null)
    {
        $homePage = $this->getHomePage($nodeId);
        $resource = $this->formProcessor->save($homePage, \Input::all());

        if (is_null($resource)) {
            return \Redirect::action(get_called_class() . '@getEdit', [$nodeId])
                ->withErrors($this->formProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action('App\Controllers\Admin\StructureController@getIndex');
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$nodeId]);
            }

            return $redirect->with('alert_success', 'Страница обновлена');
        }
    }

    /**
     * @param $nodeId
     * @return HomePage
     */
    private function getHomePage($nodeId)
    {
        $node = $this->nodeRepository->findById($nodeId);
        if (is_null($node)) {
            \App::abort(404, 'Node not found');
        }
        $homePage = \TypeContainer::getContentModelFor($node);
        if ($homePage instanceof HomePage === false) {
            \App::abort(404, 'Home page not found');
        }

        return $homePage;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBannerElement()
    {
        $content = \View::make('admin.home_pages._banner_element')
            ->with('key', \Input::get('key', 0))
            ->with('banner', $this->bannerRepository->newInstance())
            ->render();

        return \Response::json(['element' => $content]);
    }

    public function getProductAssociationBlock($productId = null, $homePageId = null)
    {
        if (is_null($productId)) {
            \App::abort(404, 'Page not found');
        }
        $association = $this->productHomePageAssociationRepo->findOrNew($homePageId, $productId);

        return \View::make('admin.home_pages._association_block')
            ->with('association', $association)
            ->with('productId', $productId);
    }

}
