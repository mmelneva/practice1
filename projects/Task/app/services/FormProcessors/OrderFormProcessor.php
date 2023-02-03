<?php namespace App\Services\FormProcessors;

use App\Models\OrderStatusConstants;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Order\OrderRepositoryInterface;
use App\Services\Validation\Order\OrderLaravelValidator;

/**
 * Class OrderFormProcessor
 * @package  App\Services\FormProcessors
 */
class OrderFormProcessor extends CreateUpdateFormProcessor
{

    public function __construct(
        OrderLaravelValidator $adminOrdersLaravelValidator,
        OrderRepositoryInterface $ordersRepository,
        CatalogProductRepositoryInterface $catalogProductRepository
    ) {
        parent::__construct($adminOrdersLaravelValidator, $ordersRepository);

        $this->catalogProductRepository = $catalogProductRepository;
    }

    /**
     * Prepare input data before validation and creating/updating.
     *
     * @param array $data
     * @return array
     */
    protected function prepareInputData(array $data)
    {
        // Prepare phone
        if (isset($data['phone'])) {
            $data['phone'] = str_replace('-', '', $data['phone']);
        }

        if (isset($data['product_id']) && !isset($data['product_name'])) {
            $product = $this->catalogProductRepository->findById($data['product_id']);

            if (!is_null($product)) {
                $data['product_name'] = $product->name;
            } else {
                unset($data['product_id']);
            }
        }

        if (!isset($data['order_status'])) {
            $data['order_status'] = OrderStatusConstants::NOVEL;
        }

        return $data;
    }

    public function create(array $data = [])
    {
        $created = parent::create($data);

        if (!is_null($created)) {
            \OrderMailsHelper::sendClientCompleteEmail($created);
            \OrderMailsHelper::sendAdminCompleteEmail($created);
        }

        return $created;
    }
}
