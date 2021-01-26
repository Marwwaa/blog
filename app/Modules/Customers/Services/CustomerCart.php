<?php 
namespace App\Modules\Customers\Services;

use App\Modules\Coupons\Models\Coupon;
use App\Modules\Customers\Models\Customer;
use HZ\Illuminate\Mongez\Traits\RepositoryTrait;

class CustomerCart
{
    use RepositoryTrait;
    
    /**
     * Customer Object
     * 
     * @var Customer 
     */
    private $customer;

    /**
     * Constructor
     * 
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Check if customer cart is empty
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->customer->cart['items']);
    }

    /**
     * Add multiple items to customer cart 
     * 
     * @param array $items
     * @return void
     */
    public function addMultiple(array $items)
    {
        foreach ($items as $item) {
            $this->cartRepository->create([
                'item' => $item['item'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? null,
                'options' => $item['options'] ?? [],
                'customer' => $this->customer,
            ]);
        }
    }

    /**
     * Clear cart
     * 
     * @return void
     */
    public function flush(): void
    {
        $this->cartRepository->flush($this->customer->id);
    }

    /**
     * Set the given coupon as current coupon for customer
     * 
     * @param CouponModel $coupon
     * @return void 
     */
    public function setCurrentCoupon(Coupon $coupon): void
    {        
        $this->customer->currentCoupon = $coupon->sharedInfo();
        $this->customer->save();
    }

    /**
     * Check if customer has a current coupon
     * 
     * @return bool
     */
    public function hasCoupon(): bool
    {
        return ! empty($this->customer->currentCoupon);
    }
}