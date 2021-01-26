<?php

namespace App\Modules\General\Controllers\Site;

use App\Modules\Meals\Resources\Meal;
use HZ\Illuminate\Mongez\Managers\ApiController;

class HomeController extends ApiController
{
    /**
     * Get Home Data
     * 
     * @return Response
     */
    public function index()
    {
        $categories = $this->categoriesRepository->published([
            'paginate' => false,
            'hasMeals' => true,
        ]);

        $this->settingsRepository->load('home');

        $homeCategories = [];
        $featuredCategories = [];

        $featuredCategoriesList = $this->settingsRepository->getValue('featuredCategories');
        
        foreach ($categories as $category) {
            $category->meals = $this->mealsRepository->published([
                'limit' => 50,
                'categories' => [$category->id],
            ]);

            if ($category->meals->isEmpty()) continue;

            $homeCategories[] = $category;

            if (in_array($category->id, $featuredCategoriesList)) {
                $featuredCategories[] = $category;
            }
        }

        return $this->success([
            'specials' => $this->mealsRepository->published([
                'id' => $this->settingsRepository->getValue('specials')
            ]),
            'featured' => $this->mealsRepository->published([
                'id' => $this->settingsRepository->getValue('featuredMeals')
            ]),
            'categories' => $homeCategories,
            'featuredCategories' => $featuredCategories,
        ]);
    }
}
