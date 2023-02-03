<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Basic\PaginateListCrudController;
use App\Controllers\Admin\Features\ToggleFlags;
use App\Models\Reviews;
use App\Services\FormProcessors\ReviewsFormProcessor\AdminReviewsFormProcessor;
use App\Services\Pagination\SimplePaginationFactory;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;
use App\Services\DataProviders\Reviews\AssociatedCategoriesReviewDataProvider;
use App\Services\DataProviders\Reviews\AssociatedProductTypePagesReviewDataProvider;

/**
 * Class ReviewsController
 * @property-read ReviewsRepositoryInterface $repository
 * @package  App\Controllers\Admin
 */
class ReviewsController extends PaginateListCrudController
{
    use ToggleFlags;

    private $catalogProductRepository;
    private $catalogCategoryRepository;
    private $productTypePageRepository;

    /**
     * @var AssociatedCategoriesReviewDataProvider
     */
    private $associatedCategoriesDataProvider;

    /**
     * @var AssociatedProductTypePagesReviewDataProvider
     */
    private $associatedProductTypePagesReviewDataProvider;



    public function __construct(
        ReviewsRepositoryInterface $repository,
        AdminReviewsFormProcessor $formProcessor,
        SimplePaginationFactory $simplePaginationFactory,
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        ProductTypePageRepositoryInterface $productTypePageRepository,
        AssociatedCategoriesReviewDataProvider $associatedCategoriesDataProvider,
        AssociatedProductTypePagesReviewDataProvider $associatedProductTypePagesReviewDataProvider
    ) {
        parent::__construct($repository, $formProcessor, $simplePaginationFactory);

        $this->catalogProductRepository = $catalogProductRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->productTypePageRepository = $productTypePageRepository;
        $this->associatedCategoriesDataProvider = $associatedCategoriesDataProvider;
        $this->associatedProductTypePagesReviewDataProvider = $associatedProductTypePagesReviewDataProvider;
     }

    protected function getTexts()
    {
        return [
            'list_title' => 'Отзывы',
            'add_new' => 'Добавить отзыв',
            'edit_title' => 'Редактирование отзыва "{name}"',
            'create_title' => 'Создание нового отзыва',
            'delete_confirm' => 'Вы уверены, что хотите удалить данный отзыв?',
        ];
    }

    protected function getMessages()
    {
        return [
            'created' => 'Новый отзыв "{name}" успешно создан',
            'updated' => 'Отзыв "{name}" обновлен',
            'deleted' => 'Отзыв "{name}" удален',
        ];
    }

    public function getViewConfiguration()
    {
        $conf = parent::getViewConfiguration();

        $conf['list'] = [
            [
                'field' => 'name',
                'template' => 'admin.list_columns._full_name_field',
                'header_template' => 'admin.list_column_headers._full_name_header',
            ],
            [
                'field' => 'comment',
                'template' => 'admin.list_columns._text_area_field',
                'header_template' => 'admin.list_column_headers._standard_header',
            ],
            [
                'field' => 'answer',
                'template' => 'admin.list_columns._text_area_field',
                'header_template' => 'admin.list_column_headers._standard_header',
            ],
            [
                'field' => 'publish',
                'template' => 'admin.list_columns._toggle_attribute',
                'header_template' => 'admin.list_column_headers._toggle_attribute_header',
            ],
            [
                'field' => 'on_home_page',
                'template' => 'admin.list_columns._toggle_attribute',
                'header_template' => 'admin.list_column_headers._toggle_attribute_header',
            ],
            [
                'field' => 'date_at',
                'template' => 'admin.list_columns._date_field',
                'header_template' => 'admin.list_column_headers._standard_header',
            ],
            [
                'template' => 'admin.list_columns._controls',
                'header_template' => 'admin.list_column_headers._control_header'
            ],
        ];

        $conf['menu'] = [
            ['field' => 'name', 'template' => 'admin.list_columns._full_name_field'],
            [
                'template' => 'admin.list_columns._controls',
                'header_template' => 'admin.list_column_headers._control_header'
            ]
        ];

        $conf['form'] = [
            ['field' => 'name', 'template' => 'admin.resource_fields._full_name_field'],
            ['field' => 'publish', 'template' => 'admin.resource_fields._checkbox_field'],
            ['field' => 'product_id', 'template' => 'admin.reviews._product_field'],
            ['field' => 'on_home_page', 'template' => 'admin.resource_fields._checkbox_field'],

            ['field' => 'email', 'template' => 'admin.resource_fields._text_field'],
            ['field' => 'comment', 'template' => 'admin.resource_fields._text_area_field'],
            ['field' => 'answer', 'template' => 'admin.resource_fields._text_area_field'],
            [
                'field' => 'date_at',
                'template' => 'admin.resource_fields._date_field'
            ],
            ['template' => 'admin.resource_fields._timestamps'],
        ];

        $conf['templates'] = ['edit' => 'admin.reviews.edit'];

        return $conf;
    }

    /**
     * @param \Eloquent|Reviews $resource
     * @return array
     */
    protected function getFormData(\Eloquent $resource)
    {
        $productVariants = $this->catalogProductRepository->getVariants();

        return compact('productVariants');
    }

    public function putToggleAttribute($id = null, $attribute = null)
    {
        return $this->checkAndPrepareToggleFlag(
            $this->repository,
            $id,
            $attribute,
            ['publish', 'on_home_page']
        );
    }

    public function getEdit($id = null)
    {
        $resource = $this->repository->findById($id);
        if (is_null($resource)) {
            \App::abort(404, 'Resource not found');
        }

        return parent::getEdit($id)
                ->with('catalogTree', $this->catalogCategoryRepository->getCatalogTree())
                ->with('pagesTree', $this->productTypePageRepository->getTree())
                ->with($this->associatedCategoriesDataProvider->getAssociatedCategoriesFormData($resource))
                ->with($this->associatedProductTypePagesReviewDataProvider->getAssociatedCategoriesFormData($resource))                ;
    }

    public function getCreate()
    {
        return parent::getCreate()
                ->with('catalogTree', $this->catalogCategoryRepository->getCatalogTree())
                ->with('pagesTree', $this->productTypePageRepository->getTree())
                ->with('attached_associated_categories', [])
                ->with('attached_associated_product_type_pages', []);
    }
}
