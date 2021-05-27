<?php

use App\Option;
use Illuminate\Support\Str;

if (!function_exists('user')) {
    function user()
    {
        return auth()->check() ? auth()->user() : optional();
    }
}

if (!function_exists('limit')) {
    function limit($value, $limit = 100, $end = '...')
    {
        return Str::limit(str_replace("<br>","",$value),$limit,$end);
    }
}

if (!function_exists('money')) {
    function money(int $val,$echo=true)
    {
        if($echo){
            return number_format($val,null,'.',',') . " تومان ";
        }else {
            return number_format($val,null,'.',',');
        }
    }
}

if (!function_exists('checkPage')) {
    function checkPage($key,$return=true){
        if($return){
            if(Str::contains($key,'*')){
                return \Request::is($key) ? 'active starPoint' : null;
            }else {
                return \Route::current()->getName() == $key ? 'active starPoint' : null;
            }
        }else{
            if(Str::contains($key,'*')){
                return \Request::is($key);
            }else {
                return \Route::current()->getName() == $key;
            }
        }
    }
}

if (!function_exists('option')) {
    function option($key=null,$value=null)
    {
        if(!is_null($key) && !is_null($value)){
            $o = Option::where('key',$key)->first();
            if($o){
                $o->update(['value'=>$value]);
            }else {
                Option::create(['key'=>$key,'value'=>$value]);
            }
            return $value;
        }
        if(!is_null($key)){
            if($o = Option::where('key',$key)->first()){
                return is_int($o->value) ? intval($o->value) : $o->value;
            }
            return null;
        }
        return null;
    }
}

if (!function_exists('render')) {
    function render($render,$class=null)
    {
        $class = $class ? $class : "justify-content-center h6 small pr-0 mb-0";
        return str_replace('"pagination"',"\"pagination {$class}\"",$render);
    }
}

if (!function_exists('contains')) {
    function contains($str,$needle)
    {
        return Str::contains($str,$needle);
    }
}


if (!function_exists('brToN')) {
    function brToN($string)
    {
        return str_replace("<br>","\n",$string);
    }
}

if (!function_exists('nToBr')) {
    function nToBr($string)
    {
        return str_replace("\n","<br>",$string);
    }
}

if (!function_exists('justBr')) {
    function justBr($string)
    {
        return strip_tags($string,'<br>');
    }
}

if (!function_exists('scriptStripper')) {
    function scriptStripper($input)
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
    }
}
if (!function_exists('send_pattern')) {
    function send_pattern($to, $pattern, $data)
    {
        $client = new SoapClient("http://37.130.202.188/class/sms/wsdlservice/server.php?wsdl");
        $user = \option('sms_user') ?? 'shatelsms';
        $pass = \option('sms_pass') ?? '123123';
        $fromNum = \option('sms_from') ?? "+98100020400";
        if (!is_array($to)) {
            $to = [$to];
        }
        $res = $client->sendPatternSms($fromNum, $to, $user, $pass, $pattern, $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('getBalance')) {
    function getBalance($user=null,$pass=null)
    {
        $client = new SoapClient("http://37.130.202.188/class/sms/wsdlservice/server.php?wsdl");
        $user = $user ?? user()->api_username;
        $pass = $pass ?? user()->api_password;
        return $client->GetCredit($user, $pass);
    }
}

if (!function_exists('is_json')) {
    function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('isActive')) {
    function isActive($return=false,\App\User $user=null)
    {
        $user = $user ?? user();
        if($user->should_pay && !$user->paid){
            return $return ? 'شما هزینه اشتراک را نپرداختید , لطفا ابتدا هزینه اشتراک را بپردازید !' : false;
        }
        if(!$user->phone_verified_at){
            return $return ? 'برای استفاده از سیستم ابتدا باید تلفن همراه خود را تایید کنید !' : false;
        }
        if(empty($user->api_username) || empty($user->api_password)){
            return $return ? "شما تنظیمات وب سرویس را وارد نکردید !" : false;
        }
        return true;
    }
}

if (!function_exists('userStatus')) {
    function userStatus($user)
    {
        if($user->should_pay && !$user->paid){
            return 'در انتظار پرداخت';
        }
        if(!$user->phone_verified_at){
            return 'در انتظار تایید موبایل';
        }
        if(empty($user->api_username) || empty($user->api_password)){
            return "در انتظار تنظیمات وب سرویس";
        }
        return 'فعال';
    }
}

if(!function_exists('sendSms')){
    function sendSms($to,$from,$msg,$user,$pass,$err=false){
        $fromNum = $from;
        $msg = urlencode($msg);
        $res = file_get_contents("http://ippanel.com/class/sms/webservice/send_url.php?from=$fromNum&to=$to&msg=$msg&uname=$user&pass=$pass");
        if($err){
            return $res;
        }else {
            if(is_json($res)){
                return json_decode($res);
            }else {
                return true;
            }
            return true;
        }
    }
}
