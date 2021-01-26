<?php

namespace App\Modules\General\Controllers\Site;

use HZ\Illuminate\Mongez\Managers\ApiController;

class SettingsController extends ApiController
{
    /**
     * {@inheritdoc}
     */
    protected $repository = 'settings';

    /**
     * Get Home Data
     * 
     * @return Response
     */
    public function index()
    {
        $settings = $this->repository->listByGroup('general', 'social', 'contact');

        $responseData = [
            'location' => [
                'lat' => 21.615276,
                'lng' => 39.161289,
            ],
        ];

        foreach ($settings as $setting) {
            if (in_array($setting->group, ['general', 'contact'])) {
                $responseData[$setting->name] = $setting->value;
            } else {
                $responseData[$setting->group][$setting->name] = $setting->value;
            }
        }

        return $this->success([
            'settings' => $responseData
        ]);

        return $this->success([
            'settings' => [
                'workingHours' => 'من 12 ص إلى 12 م - من السبت إلى الأحد',
                'location' => [
                    'lat' => 21.615276,
                    'lng' => 39.161289,
                ],
                'cancelingReasons' => [
                    'وجدت منتجات أخرى',
                    'المسافة بعيدة',
                ],
                'phoneNumber' => '+966541355243',
                'address' => 'شارع حراء',
                'email' => 'admin@egyptianRestaurant.com',
                'whatsappNumber' => '+966541355243',
                'fax' => '+966541355243',
                'social' => [
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'instagram' => 'https://instagram.com',
                    'youtube' => 'https://youtube.com',
                    'snapchat' => 'http://snapchat.com',
                ]
            ],
        ]);
    }
}
