<?php

namespace App\Modules\General\Controllers\Site;

use HZ\Illuminate\Mongez\Managers\ApiController;

class AboutUsController extends ApiController
{
    /**
     * Get Home Data
     * 
     * @return Response
     */
    public function index()
    {        
        // return $this->success([
        //     'record' => "<h1>عن المطعم</h1>\r\n<p>المطعم المصري هو مطعم يتميز <span style=\"color: rgb(147,101,184);\">بالاكلات المصرية </span></p>",
        // ]);
        return $this->success([
            'record' => $this->pagesRepository->getContentByName('about-us'),
        ]);
        return $this->success([
            'record' => '<h1>المطعم المصري</h1> <p>نبذة تعريفية عن المطعم المصري</p>',
        ]);
    }
}
