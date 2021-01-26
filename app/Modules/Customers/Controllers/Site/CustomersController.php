<?php
namespace App\Modules\Customers\Controllers\Site;

use Illuminate\Http\Request;
use HZ\Illuminate\Mongez\Managers\ApiController;

class CustomersController extends ApiController
{
    /**
     * Repository name
     * 
     * @var string
     */
    protected $repository = 'customers';

    /**
     * {@inheritDoc}
     */
    public function index(Request $request)
    {
        $options = [];

        return $this->success([
            'records' => $this->repository->list($options),
        ]);
    }
    
    /**
     * {@inheritDoc}
     */
    public function show($id, Request $request)
    {
        return $this->success([
            'record' => $this->repository->get($id),
        ]);
    }

    /**
     * Update Customer cart
     * 
     * @param  Request $request
     * @return response
     */
    public function updateCart(Request $request)
    {
        $this->repository->updateCustomerCart($request);
        return $this->success([
            'customer' => $this->repository->wrap(user()),
        ]);
    }
}