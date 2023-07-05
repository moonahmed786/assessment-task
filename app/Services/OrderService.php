<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */

    // In the order table migration, if there is no order_id field defined, it implies that the order_id is not a field present in the order table. It could be that the order table uses a different naming convention or structure.

    // Regarding the merchant association, if you create a new affiliate and the merchant ID is not defined in the data, it suggests that the affiliate might not have a direct association with a specific merchant. 
    // It's possible that the affiliate operates independently or has a different type of relationship with the merchants.

    // If you need to establish a relationship between affiliates and merchants, you might consider creating a separate table or association to track the relationship between affiliates and merchants.
    // This could involve defining foreign keys or establishing a many-to-many relationship, depending on your specific requirements.

    // Please provide more details or the existing structure of your tables if you need further assistance with the merchant association or any other aspect of your application.

    public function processOrder(array $data)
    {
        // TODO: Complete this method
        try {
            // Validate the request data
            $validator = Validator::make($data, [
                'order_id' => 'required|string',
                'subtotal_price' => 'required|numeric',
                'merchant_domain' => 'required|string',
                'discount_code' => 'required|string',
                'customer_name' => 'required|string',
                'customer_email' => 'required|email|unique:users',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Data has some error please check.',
                ], 500);
            }

            $checkEmail = User::where(['email' => $data['customer_email']])->first();
            if(!$checkEmail){
                $user = new User();
                $user->name = $data['customer_name'];
                $user->email = $data['customer_email'];
                $user->password = Hash::make('password'); // Remember to hash the password
                $user->type = User::TYPE_AFFILIATE; // Set the user type according to your constants
                if($user->save()){
                    // Register a new merchant
                    $affiliate = new Affiliate();
                    $affiliate->user_id = $user->id;
                    $affiliate->merchant_id = $merchant->id;
                    $affiliate->commissionRate = $commissionRate;
                    $affiliate->discount_code = $data['discount_code']; // Set the merchant default commission rate if you want then change it here'
                    $affiliate->save();
                    return $affiliate;
                }
            }

            

        } catch (\Exception $e) {
            // Handle the exception
            return response()->json([
                'message' => 'Affiliate registered successfully.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
