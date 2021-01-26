<?php
namespace App\Modules\Customers\Resources;

use App\Modules\Cart\Resources\Cart;
use App\Modules\AddressBook\Resources\AddressBook;
use HZ\Illuminate\Mongez\Managers\Resources\JsonResourceManager;

class Customer extends JsonResourceManager 
{
    /**
     * Data that must be returned
     * 
     * @const array
     */
    const DATA = ['id', 'name', 'email', 'phoneNumber', 'walletBalance', 'totalNotifications', 'totalOrders'];
    
    /**
     * Data that should be returned if exists
     * 
     * @const array
     */
    // const WHEN_AVAILABLE = ['verificationCode', 'accessToken', 'addresses', 'cart'];
    const WHEN_AVAILABLE = ['accessToken', 'addresses'];
    
    /**
     * Set that columns that will be formatted as dates
     * it could be numeric array or associated array to set the date format for certain columns
     * 
     * @const array
     */
    const DATES = ['createdAt' => 'd-m-Y'];
    
    /**
     * Data that has multiple values based on locale codes
     * Mostly this is used with mongodb driver
     * 
     * @const array
     */
    const LOCALIZED = [];
    
    /**
     * List of assets that will have a full url if available
     */
    const ASSETS = [];

    /**
     * Data that will be returned as a resources
     * 
     * i.e [city => CityResource::class],
     * @const array
     */
    const RESOURCES = [
        'cart' => Cart::class,
    ];
    
    /**
     * Data that will be returned as a collection of resources
     * 
     * i.e [cities => CityResource::class],
     * @const array
     */
    const COLLECTABLE = [
        'addresses' => AddressBook::class,
    ];
    
    /**
     * List of keys that will be unset before sending
     * 
     * @var array
     */
    protected static $disabledKeys = [];
    
    /**
     * List of keys that will be taken only
     * 
     * @var array
     */
    protected static $allowedKeys = [];
}