<?php namespace App\Services\Validation\HomePage;

use App\Services\Validation\Banner\BannerLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

use App\Services\Repositories\HomePage\HomePageRepositoryInterface;
use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class HomePageLaravelValidator
 * @package  App\Services\Validation\HomePage
 */
class HomePageLaravelValidator extends AbstractLaravelValidator
{
    private $bannerLaravelValidator;
    private $homePageRepository;

    public function __construct(
        ValidatorFactory $validatorFactory,
        BannerLaravelValidator $bannerLaravelValidator,
        HomePageRepositoryInterface $homePageRepository
    ) {

        parent::__construct($validatorFactory);

        $this->bannerLaravelValidator = $bannerLaravelValidator;
        $this->homePageRepository = $homePageRepository;
    }

    /**
     * @inheritdoc
     */
    public function getRules()
    {
        return [];
    }

    public function passes()
    {
        return parent::passes() && $this->passesBanners();
    }

    public function passesBanners()
    {
        $banners = array_get($this->data, 'banners', []);

        if (is_array($banners)) {
            $allPasses = true;
            foreach ($banners as $key => $data) {
                $passes = $this->bannerLaravelValidator->with($data)->passes();

                if (!$passes) {
                    $errors = $this->bannerLaravelValidator->errors();
                    foreach ($errors as $errorKey => $errorMessage) {
                        $this->errors["banners.{$key}.{$errorKey}"] = $errorMessage;
                    }
                }

                $allPasses = $allPasses && $passes;
            }
        } else {
            $allPasses = false;
        }

        return $allPasses;
    }
}
