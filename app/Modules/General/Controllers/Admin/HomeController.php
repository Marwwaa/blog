<?php

namespace App\Modules\General\Controllers\Admin;

use App\Modules\Orders\Repositories\OrdersRepository;
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
        $today = date('d-m-Y');
        $options['from'] = $today;

        $pendingOptions = $options;

        $pendingOptions['status'] = OrdersRepository::PENDING_STATUS;

        $ordersReports = $this->ordersRepository->reports();

        return $this->success([
            'total' => [
                'orders' => $ordersReports->total($options),
                'pendingOrders' =>$ordersReports->total($pendingOptions),
                'sales' => $ordersReports->totalSales($options),
                'customers' => $this->customersRepository->total($options),
            ],
            'currentWeek' => $this->ordersRepository->reports()->weeklySales($today)
        ]);
    }
}
