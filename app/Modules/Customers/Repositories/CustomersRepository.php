<?php

namespace App\Modules\Customers\Repositories;

use App\Modules\Customers\{
    Filters\Customer as Filter,
    Models\Customer as Model,
    Resources\Customer as Resource
};
use App\Modules\Customers\Models\DeviceToken;
use App\Modules\Users\Traits\Auth\AccessToken;
use Carbon\Carbon;
use HZ\Illuminate\Mongez\{
    Contracts\Repositories\RepositoryInterface,
    Managers\Database\MongoDB\RepositoryManager
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomersRepository extends RepositoryManager implements RepositoryInterface
{
    use AccessToken;

    /**
     * {@inheritDoc}
     */
    const NAME = 'customers';

    /**
     * {@inheritDoc}
     */
    const MODEL = Model::class;

    /**
     * {@inheritDoc}
     */
    const RESOURCE = Resource::class;

    /**
     * Set the columns of the data that will be auto filled in the model
     *
     * @const array
     */
    const DATA = ['name', 'email', 'phoneNumber', 'password'];

    /**
     * Auto save uploads in this list
     * If it's an indexed array, in that case the request key will be as database column name
     * If it's associated array, the key will be request key and the value will be the database column name
     *
     * @const array
     */
    const UPLOADS = [];

    /**
     * Auto fill the following columns as arrays directly from the request
     * It will encoded and stored as `JSON` format,
     * it will be also auto decoded on any database retrieval either from `list` or `get` methods
     *
     * @const array
     */
    const ARRAYBLE_DATA = [];

    /**
     * Set columns list of integers values.
     *
     * @cont array
     */
    const INTEGER_DATA = [];

    /**
     * Set columns list of float values.
     *
     * @cont array
     */
    const FLOAT_DATA = [];

    /**
     * Set columns of booleans data type.
     *
     * @cont array
     */
    const BOOLEAN_DATA = [];

    /**
     * Set the columns will be filled with single record of collection data
     * i.e [country => CountryModel::class]
     *
     * @const array
     */
    const DOCUMENT_DATA = [];

    /**
     * Set the columns will be filled with array of records.
     * i.e [tags => TagModel::class]
     *
     * @const array
     */
    const MULTI_DOCUMENTS_DATA = [];

    /**
     * Add the column if and only if the value is passed in the request.
     *
     * @cont array
     */
    const WHEN_AVAILABLE_DATA = [];

    /**
     * Set all filter class you will use in this module
     * 
     * @const array 
     */
    const FILTERS = [
        Filter::class
    ];

    /**
     * Filter by columns used with `list` method only
     *
     * @const array
     */
    const FILTER_BY = [
        'int' => [
            'id'
        ],
        'like' => [
            'name',
            'phoneNumber',
            'email',
        ],
    ];

    /**
     * Determine wether to use pagination in the `list` method
     * if set null, it will depend on pagination configurations
     *
     * @const bool
     */
    const PAGINATE = null;

    /**
     * Number of items per page in pagination
     * If set to null, then it will taken from pagination configurations
     *
     * @const int|null
     */
    const ITEMS_PER_PAGE = null;

    /**
     * Set any extra data or columns that need more customizations
     * Please note this method is triggered on create or update call
     *
     * @param   mixed $model
     * @param   \Illuminate\Http\Request $request
     * @return  void
     */
    protected function setData($model, $request)
    {
        //
        if (!$model->id) {
            // generate 6 digits
            $model->verificationCode = mt_rand(100000, 999999);
            $model->isVerified = false;
            $model->cart = [
                'items' => [],
            ];
        }

        if (!$model->walletBalance) {
            $model->walletBalance = 0;
        }

        $model->isVerified = true;
    }

    /**
     * {@inheritDoc}
     */
    public function onCreate($user, $request)
    {
        $this->generateAccessToken($user, $request);

        if ($request->cart) {
            $user->getCart()->addMultiple($request->cart);
        }
    }

    /**
     * Update total orders for the given customer
     * 
     * @param  Model $customer
     * @return void 
     */
    public function updateTotalOrders($customer)
    {
        $customer->totalOrders = $this->ordersRepository->getTotal([
            'customer' => $customer->id,
        ]);

        $customer->save();
    }

    /**
     * Check if customer can login
     *
     * @param  Request $request
     * @return Resource|false
     */
    public function login(Request $request)
    {
        $filter = [];

        $value = $request->emailOrPhone;

        // if the phone number is numeric, then search in phone numbers
        // otherwise search in email
        if (is_numeric($value)) {
            $filter[] = 'phoneNumber';
        } else {
            $filter[] = 'email';
        }

        $filter[] = $value;

        // $customer = Model::where(...$filter)->where('isVerified', 1)->first();
        $customer = Model::where(...$filter)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) return false;

        $accessToken = $this->generateAccessToken($customer);

        $customer->accessToken = $accessToken;

        if ($request->cart) {
            $customer->getCart()->addMultiple($request->cart);
        }

        return $this->wrap($customer);
    }

    /**
     * Verify Account by the given verification code
     *
     * @param  int $verificationCode
     * @return Resource|false
     */
    public function verify(int $verificationCode)
    {
        $customer = $this->getBy('verificationCode', (int) $verificationCode);

        if (!$customer) return false;

        // clear the verification code
        $customer->verificationCode = null;
        $customer->isVerified = true;

        $this->generateAccessToken($customer);

        return $customer;
    }

    /**
     * Do any extra filtration here
     *
     * @return  void
     */
    protected function filter()
    {
        //
    }

    /**
     * Update customer wallet balance
     * 
     * @param int $customerId
     * @return void
     */
    public function updateWalletBalance(int $customerId)
    {
        $user = user();

        if ($user->AccountType() === 'customer' && $user->id === $customerId) {
            $customer = $user;
        } else {
            $customer = $this->getModel($customerId);
        }

        $customer->walletBalance = $this->walletsRepository->getBalanceFor($customer->id, 'deposit') 
                                   -
                                   $this->walletsRepository->getBalanceFor($customer->id, 'withdraw');

        $customer->save();
    }

    /**
     * Add new device to customer 
     * Device options contains: type: ios|android, token: string 
     * 
     * @param  Model $customer
     * @param  array $deviceOptions
     * @return void
     */
    public function addNewDeviceToken(Model $customer, array $deviceOptions)
    {
        if ($this->getDeviceToken($customer, $deviceOptions)) return;

        $deviceToken = new DeviceToken([
            'customerId' => $customer->id,
            'type' => $deviceOptions['type'],
            'token' => $deviceOptions['token'],
        ]);

        $deviceToken->save();

        $customer->associate($deviceToken, 'devices')->save();
    }

    /**
     * Remove device from customer 
     * 
     * @param  Model $customer
     * @param  array $deviceOptions
     * @return void
     */
    public function removeDeviceToken(Model $customer, array $deviceOptions)
    {
        $deviceToken = $this->getDeviceToken($customer, $deviceOptions);

        if (!$deviceToken) return;

        $customer->disassociate($deviceToken, 'devices')->save();

        $deviceToken->delete();
    }

    /**
     * Get device token for the given customer and device options
     * 
     * @param Model $customer
     * @param array $deviceOptions
     * @return DeviceToken
     */
    public function getDeviceToken(Model $customer, array $deviceOptions): ?DeviceToken
    {
        return DeviceToken::where('token', $deviceOptions['token'])->where('customerId', $customer->id)->where('type', $deviceOptions['type'])->first();
    }

    /**
     * Update customer cart
     *
     * @param Request $request
     * @return  void
     */
    public function updateCustomerCart(Request $request)
    {
        $customer = user();

        if ($request->cart) {
            $meals = array_map(function ($item) {
                return [
                    'id' => (int) $item['id'],
                    'quantity' => (int) ($item['quantity'] ?? 0),
                ];
            }, $request->cart['items'] ?? []);
        } elseif ($request->meals) {
            $meals = array_map(function ($item) {
                return [
                    'id' => (int) $item['id'],
                    'quantity' => (int) ($item['quantity'] ?? 0),
                ];
            }, $request->meals);
        }

        $mealsList = $this->mealsRepository->list([
            'as-model' => true,
            'id' => array_map(function ($meal) {
                return $meal['id'];
            }, $meals)
        ]);

        $cartItems = [];

        foreach ($mealsList as $meal) {
            $quantity = 0;

            foreach ($meals as $mealInfo) {
                if ($mealInfo['id'] == $meal->id) {
                    $quantity = $mealInfo['quantity'];
                    break;
                }
            }

            if (!$quantity) continue;

            $data = $meal->sharedInfoPublic();

            $data['quantity'] = $quantity;

            $cartItems[] = $data;
        }

        $customer->cart = [
            'items' => $cartItems,
        ];

        $customer->save();
    }

    /**
     * Get total customers based on given options
     *
     * @param  array $options
     * @return int
     */
    public function total(array $options): int
    {
        $query = $this->getQuery();

        if (!empty($options['from'])) {
            $query->where('createdAt', '>=', Carbon::parse($options['from']));
        }

        if (!empty($options['to'])) {
            $query->where('createdAt', '<=', Carbon::parse($options['to']));
        }

        return $query->count();
    }
}
