<?php namespace App\Services\Repositories\Banner;

use App\Models\HomePage;

interface BannerRepositoryInterface
{
    public function createOrUpdate($homePageId, $bannerId, array $bannerData = []);

    public function deleteNotIn($homePageId, array $bannerIdList);

    public function newInstance(array $data = array(), $exists = false);

    /**
     * Get banners for home page
     *
     * @param HomePage $homePage
     * @return mixed
     */
    public function getBannersForHomePage(HomePage $homePage);

    /**
     * Get published banners with image for home page
     *
     * @param HomePage $homePage
     * @return mixed
     */
    public function getPublishedBannersForHomePage(HomePage $homePage);
}
