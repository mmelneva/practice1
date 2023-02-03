<?php
namespace App\Controllers\Admin;

use App\Controllers\Admin\Features\ToggleFlags;
use App\Services\Repositories\ProductGalleryImage\ProductGalleryImageRepositoryInterface;
use App\Services\Repositories\ToggleableRepositoryInterface;
use App\Models\ProductGalleryImage;

/**
 * Class CatalogProductGalleryController
 * @package App\Controller\Admin
 */
class CatalogProductGalleryController extends \BaseController
{
    use ToggleFlags;

    /** @var ProductGalleryImageRepositoryInterface */
    private $galleryImageRepository;

    /**
     * @param ProductGalleryImageRepositoryInterface $galleryImageRepository
     */
    public function __construct(ProductGalleryImageRepositoryInterface $galleryImageRepository) {
        $this->galleryImageRepository = $galleryImageRepository;
    }

    /**
     * Toggle attribute
     * @param $id
     * @param $attribute
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function putToggleAttribute($id, $attribute)
    {
        return $this->checkAndPrepareToggleFlag(
            $this->galleryImageRepository,
            $id,
            $attribute,
            ['publish']
        );
    }

    protected function prepareToggleFlag(
        ToggleableRepositoryInterface $repository,
        $id,
        $attribute,
        $action = 'putToggleAttribute'
    ) {
        $modelInstance = $repository->toggleAttribute($id, $attribute);
        if (!$modelInstance) {
            \App::abort(404, 'Resource not found');
        }

        $modelInstance->product->touch();

        if (\Request::ajax()) {
            $view = 'admin.shared._list_flag';
            $newFlagView = \View::make($view)
                ->with('element', $modelInstance)
                ->with('action', action(get_called_class() . '@' . $action, [$modelInstance->id, $attribute]))
                ->with('attribute', $attribute)
                ->render();

            $newImageFieldsView = \View::make('admin.catalog_products._gallery_images._gallery_image_fields')
                ->with('image', $modelInstance)
                ->render();

            return \Response::json(['new_icon' => $newFlagView, 'new_image_fields' => $newImageFieldsView]);
        } else {
            return \Redirect::action(
                'App\Controllers\Admin\CatalogProductsController@getEdit',
                [$modelInstance->product->id]
            );
        }
    }

    /**
     * Delete
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDestroy($id)
    {
        /** @var ProductGalleryImage $image */
        $image = $this->galleryImageRepository->findById($id);

        if (is_null($image)) {
            \App::abort(404, 'Image not found');
        }

        if (\Request::ajax()) {
            $image->delete();
            $newGalleryImageListView = \View::make('admin.catalog_products._gallery_images._gallery_image_list')
                ->with('images', $this->galleryImageRepository->allForProduct($image->product))
                ->with('opened_images', [])
                ->render();


            return \Response::json(['new_gallery_image_list' => $newGalleryImageListView]);
        } else {
            return \Redirect::action(
                'App\Controllers\Admin\CatalogProductsController@getEdit',
                [$image->product->id]
            );
        }
    }

    /**
     * Get block for new dot
     * @return \Illuminate\View\View
     */
    public function getNewImageBlock()
    {
        if (\Request::ajax()) {
            $number = \Input::get('generate_id');
            $uniqueId = 'new_' . (is_null($number) ? uniqid() : $number);

            $image = $this->galleryImageRepository->newInstance();
            $image->id = $uniqueId;

            return \View::make('admin.catalog_products._gallery_images._gallery_image')
                ->with('image', $image)
                ->with('opened_images', [$uniqueId]);
        } else {
            return \Redirect::back();
        }
    }
}
