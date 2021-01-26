<?php

namespace App\Modules\Customers\Models;

use App\Modules\Customers\Services\Coupon;
use App\Modules\Customers\Services\CustomerCart;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Modules\Users\Traits\Auth\UpdatePassword;
use HZ\Illuminate\Mongez\Traits\MongoDB\RecycleBin;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use HZ\Illuminate\Mongez\Managers\Database\MongoDB\Model;

class Customer extends Model implements Authenticatable
{
    use AuthenticatableTrait, UpdatePassword, RecycleBin;

    /**
     * {@Inheritdoc}
     */
    const SHARED_INFO = ['id', 'name'];

    /**
     * Customer cart
     */
    protected static $customerCart;
    
    /**
     * Customer coupon
     */
    protected static $coupon;
    
    /**
     * {@inheritDoc}
     */
    public function accountType(): string
    {
        return 'customer';
    }
    
    /**
     * Get cart manager for current user
     * 
     * @return CustomerCart
     */
    public function getCart(): CustomerCart
    {
        if (! static::$customerCart) {
            static::$customerCart = new CustomerCart($this);
        }

        return static::$customerCart;
    }
    
    /**
     * Get current customer coupon manager
     * 
     * @return Coupon
     */
    public function coupon(): Coupon
    {
        if (! static::$coupon) {
            static::$coupon = new Coupon($this);
        }

        return static::$coupon;
    }

    /**
     * Get customer devices ids for firebase
     * 
     * @return array
     */
    public function getFireBaseDevicesIds(): array
    {
        return collect($this->devices)->pluck('token')->toArray();
    }
}