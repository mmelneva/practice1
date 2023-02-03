<?php namespace App\Services\FormProcessors\HomePagesSubProcessor;

use App\Models\HomePage;
use App\Services\Repositories\Banner\BannerRepositoryInterface;

/**
 * Class BannersSubProcessor
 * @package  App\Services\FormProcessors\HomePagesSubProcessor
 */
class BannersSubProcessor implements HomePageSubProcessorInterface
{
    private $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function prepareInputData($inputData)
    {
        return $inputData;
    }

    public function process(HomePage $homePage, $formData)
    {
        $bannersData = array_get($formData, 'banners', []);

        $bannersIdList = [];

        foreach ($bannersData as $bannerData) {
            $banner = $this->bannerRepository->createOrUpdate(
                $homePage->id,
                array_get($bannerData, 'id'),
                $bannerData
            );

            $bannersIdList[] = $banner->id;
        }

        $this->bannerRepository->deleteNotIn($homePage->id, $bannersIdList);
    }
}
