<?php namespace App\Services\Repositories\Banner;

use App\Models\Banner;
use App\Models\HomePage;

/**
 * Class EloquentBannerRepository
 * @package  App\Services\Repositories\Banner
 */
class EloquentBannerRepository implements BannerRepositoryInterface
{
    const POSITION_STEP = 10;

    protected $modelInstance;

    public function __construct()
    {
        $this->modelInstance = new Banner;
    }

    /**
     * @inheritDoc
     */
    public function createOrUpdate($homePageId, $bannerId, array $bannerData = [])
    {
        if (empty($bannerData['position'])) {
            $maxPosition = $this->modelInstance->where('home_page_id', $homePageId)->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }
            $bannerData['position'] = $maxPosition + self::POSITION_STEP;
        }

        $existingBanner = $this->modelInstance->where('home_page_id', $homePageId)
            ->where('id', $bannerId)->first();

        if (is_null($existingBanner)) {
            /** @var Banner $existingBanner */
            $existingBanner = $this->modelInstance->newInstance();
            $existingBanner->home_page_id = $homePageId;
        }

        $existingBanner->fill($bannerData);
        $existingBanner->save();

        return $existingBanner;
    }

    /**
     * @inheritDoc
     */
    public function deleteNotIn($homePageId, array $bannerIdList)
    {
        $query = $this->modelInstance->where('home_page_id', $homePageId);
        if (count($bannerIdList) > 0) {
            $query->whereNotIn('id', $bannerIdList);
        }
        $modelInstances = $query->get();
        /** @var Banner $modelInstance */
        foreach ($modelInstances as $modelInstance) {
            $modelInstance->delete();
        }
    }

    /**
     * @inheritDoc
     */
    public function newInstance(array $data = array(), $exists = false)
    {
        return $this->modelInstance->newInstance($data, $exists);
    }

    public function getBannersForHomePage(HomePage $homePage)
    {
        $query = $this->getBannersQuery($homePage);

        return $query->get();
    }

    public function getPublishedBannersForHomePage(HomePage $homePage)
    {
        $query = $this->getBannersQuery($homePage);
        $this->scopePublished($query);

        return $query
            ->where('banners.image', '<>', '')
            ->whereNotNull('banners.image')
            ->get()->filter(
            function ($banner) {
                return with($banner->getAttachment('image'))->exists();
            }
        );
    }

    /**
     * @param HomePage $homePage
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    private function getBannersQuery(HomePage $homePage)
    {
        $query = $homePage->banners();
        $this->scopeOrdered($query);

        return $query;
    }

    private function scopeOrdered($query)
    {
        return $query->orderBy('banners.position');
    }

    private function scopePublished($query)
    {
        return $query->where('banners.publish', true);
    }
}
