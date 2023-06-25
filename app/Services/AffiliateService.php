<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {}

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        // TODO: Complete this method
        try {
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->password = Hash::make('password'); // Remember to hash the password
                $user->type = User::TYPE_AFFILIATE; // Set the user type according to your constants
                if($user->save()){
                    // Register a new merchant
                    $affiliate = new Affiliate();
                    $affiliate->user_id = $user->id;
                    $affiliate->merchant_id = $merchant->id;
                    $affiliate->commissionRate = $commissionRate;
                    $affiliate->discount_code = 'AHMED'; // Set the merchant default commission rate if you want then change it here'
                    $affiliate->save();
                }

            return $affiliate;

        } catch (\Exception $e) {
            // Handle the exception
            return response()->json([
                'message' => 'Affiliate registered successfully.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
