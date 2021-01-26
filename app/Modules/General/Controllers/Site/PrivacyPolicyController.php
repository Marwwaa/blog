<?php

namespace App\Modules\General\Controllers\Site;

use HZ\Illuminate\Mongez\Managers\ApiController;

class PrivacyPolicyController extends ApiController
{
    /**
     * Get Home Data
     * 
     * @return Response
     */
    public function index()
    {        
        return $this->success([
            'record' => $this->pagesRepository->getContentByName('privacy-policy'),
        ]);
    }
}
