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
use Illuminate\Validation\Rule;

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
                
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'commissionRate' => $commissionRate
                ];
                // Validate the request data
                $validator = Validator::make($data, [
                    'name' => 'required|string',
                    'email' => 'required|email|max:255|unique:users',
                    'commissionRate' => 'required|numeric'
                ]);
                if ($validator->fails() == true) {
                    return response()->json([
                        'message' => 'Data has some error please check.',
                    ], 500);
                }

                
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = Hash::make('password'); // Remember to hash the password
                $user->type = User::TYPE_AFFILIATE; // Set the user type according to your constants
                if($user->save()){
                    // \DB::connection()->enableQueryLog();
                    // Register a new merchant
                    // dd($merchant);
                    $affiliate = new Affiliate();
                    $affiliate->user_id = $user->id;
                    $affiliate->merchant_id = $merchant->id;
                    $affiliate->commission_rate = $commissionRate;
                    $affiliate->discount_code = ""; // Set the merchant default commission rate if you want then change it here'
                    $affiliate->save();
                    // $queries = \DB::getQueryLog();
                    // dd($queries);
                    return $affiliate;
                    // dd($affiliate);
                }

        } catch (\Exception $e) {
            // Handle the exception
            dd($e->getMessage());
            return response()->json([
                'message' => 'Affiliate registered successfully.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
