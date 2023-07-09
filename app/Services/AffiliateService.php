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
                // dd($validator);
                if ($validator->fails() == true) {
                    throw new AffiliateCreateException('Data has some error please check.');
                }
                
                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    $user->password = Hash::make('password'); // Remember to hash the password
                    $user->type = User::TYPE_AFFILIATE; // Set the user type according to your constants
                    if($user->save()){
                        $affiliate = new Affiliate();
                        $affiliate->user_id = $user->id;
                        $affiliate->merchant_id = $merchant->id;
                        $affiliate->commission_rate = $commissionRate;
                        $affiliate->discount_code = ""; // Set the merchant default commission rate if you want then change it here'
                        $affiliate->save();
                        return $affiliate;
                    }
                
    }
}
