<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail, Auth, Request, DB;

class Stripe extends Model
{

    public static $stripe_mode = 'test';

    public static $stripe_api_key = [
        'live' => array(
            'publishable_key' => 'pk_live_poNtFfhexPzfsfxPiiHaHHUk', // Dental Account
            'secret_key'      => 'sk_live_hm7gmbIXm6rMLRgU7g4hLt0d',
        ),
        'test' => array(
            'publishable_key' => 'pk_test_fY3GMPqaZTKE94kLMB5BnOdf',
            'secret_key'      => 'sk_test_RXv2cjYIBVyIWk8wEdLnIkf2'
        )
    ];      

    public function stripe_create_charge($data = array(), $metadata = array()) {

        require_once base_path('vendor/stripe-pay/init.php');
        
        $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        try {

            /* Generate Token */
            $token =  \Stripe\Token::create(array(
                "card" => array(
                    "name"      => $metadata['credit_card_holder'],
                    "number"    => $metadata['credit_card_number'],
                    "exp_month" => $metadata['credit_card_month'],
                    "exp_year"  => $metadata['credit_card_year'],
                    "cvc"       => $metadata['credit_card_code'],
              )
            ));

            if($token) {

                /* Create Customer */
                $customer = \Stripe\Customer::create(array(
                    'email' => $metadata['billing_email'],
                    'card'  => $token->id,
                    'metadata' => $data
                ));

                /* Create Charge */
                $charge = \Stripe\Charge::create(array(
                  "amount" => $metadata['total'] * 100,
                  "currency" => "usd",
                  "source" => 'tok_'.get_cc_type($metadata['credit_card_number']),
                  "description" => "Payment for custom T-shirt ordered by ".$metadata['billing_email']
                ));

                $stripe_data = array(
                    'payment'      => 'stripe',
                    'token'        => $token->id,
                    'customer'     => $customer->id,
                );

                return $stripe_data; 

            }

        } catch(\Exception $e) {

            return array(
                'payment' => false,
                'msg' => $e->getMessage()
            );
        }


    }


    public function stripe_update_card($customer, $data = array()) {


        try {

            require_once base_path('vendor/stripe-pay/init.php');

        
            $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

            \Stripe\Stripe::setApiKey($stripe['secret_key']);

            /* Generate Token */
            $token =  \Stripe\Token::create(array(
                "card" => array(
                "number"    => $data['credit_card_number'],
                "exp_month" => $data['credit_card_month'],
                "exp_year"  => $data['credit_card_year'],
                "cvc"       => $data['credit_card_code']
              )
            ));

            $customer = \Stripe\Customer::retrieve($customer);
            $customer->source = $token->id;
            $customer->save();

        } catch(\Exception $e) {

            return array(
                'payment' => false,
                'msg' => $e->getMessage()
            );
        }

    
    }

}
