<?php
namespace App\Helpers;
use Auth;
use App\Models\Setting;
class Money
{
    public static function getSetting($key='')
    {
        $value = '';
        if($key)
        {
            $setting = Setting::where(['key'=>$key])->first();
            $value = isset($setting->value) ? $setting->value:'';
        }
        return $value;
    }
    public static function getAmount($amount, $length = 0)
    {
        if(0 < $length){
            return round($amount + 0, $length);
        }
        return $amount + 0;
    }
    public static function formatAmount($amount = 0)
    {
        return str_replace(',','',$amount);
    }
    public static function get_stro_bank_transfer($post_data = array())
    {
        $public_key = env('STRO_PUBLIC_KEY');
        $api_url = env('STRO_WALLET_API_URL').'/api/banks/request';
        $host = $api_url;
        $curl= curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_data,
        ));
        $s = curl_exec($curl);
        $apiresponse = json_decode($s);
        return $apiresponse;
    }
    public static function get_stro_account_name($bank_code = null,$account_number = null)
    {
        $public_key = env('STRO_PUBLIC_KEY');
        $api_url = env('STRO_WALLET_API_URL').'/api/banks/get-customer-name?public_key='.$public_key.'&account_number='.$account_number.'&bank_code='.$bank_code;
        $host = $api_url;
        $curl= curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $s = curl_exec($curl);
        $apiresponse = json_decode($s);
        return $apiresponse;
    }
    public static function get_stro_bank_list()
    {
        $public_key = env('STRO_PUBLIC_KEY');
        $api_url = env('STRO_WALLET_API_URL').'/api/banks/lists?public_key='.$public_key;
        $host = $api_url;
        $curl= curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $s = curl_exec($curl);
        $apiresponse = json_decode($s);
        return $apiresponse;
    }
    public static function get_cable_variation_codes($service_id = null)
    {
        $public_key = env('STRO_PUBLIC_KEY');
        $api_url = env('STRO_WALLET_API_URL').'/api/cable-subscription/plans?public_key='.$public_key.'&service_id='.$service_id;
        $host = $api_url;
        $curl= curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $s = curl_exec($curl);
        $apiresponse = json_decode($s);
        return $apiresponse;
    }
    public function value($val,  $currency_symbol = null, $is_crypto = 0)
    {
      if ($is_crypto == 1) {
     		return  $currency_symbol .' '. $this->trimzero($val) ;
      }
       return  $currency_symbol .' '.number_format((float)$val, 2, '.', ',')  ;
    }
    
    public static function instance()
    {
       return new Money();
    }
    public static function getTrx($length = 12)
    {
        $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    private function trimzero( $val )
    {
        preg_match( "#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($val), $o );
        return $o[1].sprintf('%d',$o[2]).($o[3]!='.'?$o[3]:'');
    }
    
}