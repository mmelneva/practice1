<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Order;
use App\Services\FormProcessors\OrderFormProcessor;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;

/**
 * Class OrdersController
 * @package  App\Controllers\Client
 */
class OrdersController extends BaseController
{
    /**
     * @var CatalogCategoryRepositoryInterface
     */
    private $catalogCategoryRepository;

    /**
     * @param OrderFormProcessor $formProcessor
     * @param CatalogCategoryRepositoryInterface $catalogCategoryRepository
     */
    public function __construct(
        OrderFormProcessor $formProcessor,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository
    ) {
        $this->formProcessor = $formProcessor;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
    }

    public function postStore()
    {
        $status = 'ERROR';
        $content = '';
        if (\Request::ajax()) {
            $inputData = \Input::all();

            /** @var Order $createdOrder */
            $createdOrder = $this->formProcessor->create($inputData);
            if (!is_null($createdOrder)) {
                $status = 'OK';
                $content = '<div class="success">' . trans('validation.model_attributes.order.form.success') . '</div>';
            } else {
                $errorsArray = [];
                $errors = $this->formProcessor->errors();
                foreach ($errors as $error) {
                    $errorsArray = array_merge($errorsArray, $error);
                }
                if (count($errorsArray) > 0) {
                    $content = '<div class="errors">' . implode('<br>', $errorsArray) . '</div>';
                }
            }
        }

        return \Response::json(compact('status', 'content'));
    }
}
