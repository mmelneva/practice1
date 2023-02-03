<?php namespace App\Services\DataProviders\HomePage;

use App\Models\HomePage;
use App\Services\Repositories\Banner\BannerRepositoryInterface;

/**
 * Class BannersDataProvider
 * @package  App\Services\DataProviders\HomePage
 */
class BannersDataProvider
{
    public function __construct(
        BannerRepositoryInterface $bannerRepository
    ) {
        $this->bannerRepository = $bannerRepository;
    }

    public function getBannersFormData(HomePage $homePage, array $oldData = [])
    {
        $bannersData = [];
        foreach (array_get($oldData, 'banners', []) as $key => $data) {
            $bannersData[$key] = $this->bannerRepository->newInstance($data);
        }

        array_set($oldData, 'banners', $bannersData);

        if (count($bannersData) == 0) {
            $bannersData = $this->bannerRepository->getBannersForHomePage($homePage);
        }

        return ['banners' => $bannersData];
    }
}
