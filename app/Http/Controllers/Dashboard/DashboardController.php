<?php

namespace App\Http\Controllers\Dashboard;

use App\Contact;
use App\Event;
use App\Exports\NumbersExport;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use SoapClient;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function settings()
    {
        return view('Others.settings');
    }

    public function webservice()
    {
        return view('Others.webservice');
    }

    public function settingsUpdate(Request $request)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);
        if($request->password && $request->new_password){
            if(Hash::check($request->password, user()->password)){
                user()->update([
                    'password' => bcrypt($request->password)
                ]);
                alert()->success('تبریک !','رمز عبور شما با موفقیت تغیر یافت');
            }else {
                alert()->error('خطا!','رمز عبور فعلی شما صحیح نیست !');
            }
        }else {
            alert()->success('تبریک !','نام شما با موفقیت تغیر یافت !');
        }
        user()->update([
            'name' => $request->name
        ]);
        return redirect()->route('settings');
    }

    public function webserviceUpdate(Request $request)
    {
        $this->validate($request,[
            'api_username' => 'required' ,
            'api_password' => 'required'
        ]);
        $balance = getBalance($request->api_username,$request->api_password);
        if(is_json($balance)){
            alert()->error('خطا !','اطلاعات وبسرویس اشتباه هست , لطفا دوباره تلاش کنید !');
        }elseif(is_int(intval($balance))) {
            user()->update([
                'api_username' => $request->api_username,
                'api_password' => $request->api_password,
                'api_ads_sender' => $request->api_ads_sender,
                'api_notify_sender' => $request->api_notify_sender,
                'api_sim_sender' => $request->api_sim_sender
            ]);
            alert()->success('تبریک !','تنظیمات وبسرویس با موفقیت ذخیره شد !');
        }
        return redirect()->route('webservice');
    }

    public function verifyPhone(Request $request)
    {
        if($request->phone || $request->code){
            if($request->op == 'sendCode'){
                if(cache()->has('phone.'.user()->id)){
                    return response()->json([
                        'status' => false,
                        'msg' => 'برای تلاش مجدد باید حداقل 1 دقیقه صبر کنید !'
                    ]);
                }elseif($request->phone == user()->phone && user()->phone_verified_at){
                    return response()->json([
                        'status' => false,
                        'msg' => 'این شماره قبلا توسط سیستم تایید شده !'
                    ]);
                }else {
                    $code = round(rand(1000,5000));
                    cache()->put('phone.'.user()->id,$code,now()->addMinutes(1));
                    cache()->put('phone.'.user()->id.'.number',$request->phone,now()->addMinutes(2));
                    $res = send_pattern($request->phone,246,['code'=>$code,'app_name'=>'فراز اسمس']);
                    if($res){
                        return response()->json([
                            'status' => true,
                            'msg' => 'کد با موفقیت ارسال شد !'
                        ]);
                    }else {
                        return response()->json([
                            'status' => false,
                            'msg' => 'خطایی در سیستم پیش آمده , لطفا با پشتیبان سایت تماس بگیرید !'
                        ]);
                    }
                }
            }elseif($request->op == 'verifyPhone') {
                if(cache()->has('phone.'.user()->id)){
                    $code = cache('phone.'.user()->id);
                    if($request->code == $code){
                        user()->update([
                            'phone' => cache('phone.'.user()->id.'.number'),
                            'phone_verified_at' => now()
                        ]);
                        return response()->json([
                            'status' => true,
                            'msg' => 'شماره شما با موفقیت تایید شد !',
                            'phone' => cache('phone.'.user()->id.'.number')
                        ]);
                    }else {
                        return response()->json([
                            'status' => false,
                            'msg' => 'کد مورد نظر نادرست است , لطفا دوباره تلاش کنید !',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'msg' => 'کد مورد نظر منقضی شده , لطفا مجددا تلاش فرمایید !'
                    ]);
                }
            }else {
                return response()->json([
                    'status' => false,
                    'msg' => 'درخواست نا معتبر !'
                ]);
            }
        }else {
            return response()->json([
                'status' => false,
                'msg' => 'ورود شماره تلفن ضروری است !'
            ]);
        }
    }

    public function contacts()
    {
        return view('Others.contacts');
    }

    public function contactsStore(Request $request)
    {
        $item = user()->contacts()->whereName($request->name);
        if($item->count()){
            return response()->json([
                'status' => false,
                'msg' => 'این دفترچه در حال حاضر وجود دارد !'
            ]);
        }
        $item = user()->contacts()->create([
            'name' => $request->name,
        ]);
        return response()->json([
            'status' => true,
            'id' => $item->id
        ]);
    }

    public function contactsUpdate(Request $request)
    {
        $item = user()->contacts()->whereId($request->id);
        if($item->first()->user_id != user()->id){
            return response()->json([
                'status' => false,
                'msg' => 'دسترسی غیر مجاز !'
            ]);
        }
        Contact::whereId($request->id)->first()->update([
            'name' => $request->name,
        ]);
        return response()->json([
            'status' => true
        ]);
    }

    public function contactsDelete(Request $request)
    {
        $item = user()->contacts->where('id',$request->id);
        if($item->first()->user_id != user()->id){
            return response()->json([
                'status' => false,
                'msg' => 'دسترسی غیر مجاز !'
            ]);
        }
        if(!$item->count()){
            return response()->json([
                'status' => false,
                'msg' => 'این دفترچه قبلا حذف شده !'
            ]);
        }
        $item = $item->first();
        if($item->numbers()->count()){
            $item->numbers()->delete();
        }
        if($item->events()->count()){
            $item->events()->delete();
        }
        $item->delete();
        return response()->json([
            'status' => true,
            'msg' => 'با موفقیت حذف شد !'
        ]);
    }

    public function contactsAdd(Request $request)
    {
        if(empty($request->phone)){
            return response()->json([
                'status' => false,
                'msg' => 'شماره وارد شده صحیح نیست !'
            ]);
        }
        $phone = user()->contacts()->whereId($request->id)->first()->numbers()->whereNumber($request->phone);
        if($phone->count()){
            return response()->json([
                'status' => false,
                'msg' => 'این شماره قبلا به دفترچه اضافه شده !'
            ]);
        }
        $phone = user()->contacts()->whereId($request->id)->first()->numbers()->create([
            'number' => $request->phone
        ]);
        return response()->json([
            'status' => true,
            'msg' => 'شماره با موفقیت به دفترچه اضافه شد !'
        ]);
    }

    public function contactsNumbers(Request $request)
    {
        $item = user()->contacts()->whereId($request->id);
        if($item->first()->user_id != user()->id){
            return response()->json([
                'status' => false,
                'msg' => 'دسترسی غیر مجاز !'
            ]);
        }
        return response()->json([
            'numbers'=> $item->first()->numbers()->pluck('number')->toArray(),
            'status' => true
        ]);
    }

    public function events()
    {
        return view('Others.events');
    }

    public function eventsStore(Request $request)
    {
        $empty = true;
        if($request->hour || $request->day || $request->minute || $request->month || $request->year){
            $empty = false;
        }
        if($empty){
            return response()->json([
                'status' => false,
                'msg' => 'تاریخ وارد شده معتبر نیست , لطفا یک تاریخ صحیح وارد کنید !'
            ]);
        }

        if(empty($request->msg)){
            return response()->json([
                'status' => false,
                'msg' => 'متن پیامک خالی است , یک متن وارد کنید !'
            ]);
        }

        if(empty($request->contact_id)){
            return response()->json([
                'status' => false,
                'msg' => 'دفترچه ای را انتخاب کنید !'
            ]);
        }

        if(empty($request->from)){
            return response()->json([
                'status' => false,
                'msg' => 'خط ارسال کننده خالی است , یک خط وارد کنید یا از طریق تنظیمات وب سرویس خط دلخواه خود را وارد نمایید .'
            ]);
        }
        $fireOn = now()->addHours(intval($request->hour))->addDays(intval($request->day))->addMinutes(intval($request->minute))->addMonths(intval($request->month))->addYears(intval($request->year));
        $item = Event::create([
            'contact_id' => $request->contact_id,
            'user_id' => user()->id,
            'hour' => $request->hour,
            'day' => $request->day,
            'minute' => $request->minute,
            'month' => $request->month,
            'year' => $request->year,
            'fire_on' => $fireOn,
            'msg' => $request->msg,
            'from' => $request->from,
            'uid' => bcrypt(time())
        ]);
        $item = Event::find($item->id)->with('contact');
        return response()->json([
            'status' => true,
            'object' => $item->first()->toJson(),
            'msg' => 'رویداد مورد نظر با موفقیت ایجاد شد !'
        ]);
    }

    public function eventsDelete(Request $request)
    {
        $item = user()->events()->whereId($request->id);
        if($item->first()->user_id != user()->id){
            return response()->json([
                'status' => false,
                'msg' => 'دسترسی غیر مجاز !'
            ]);
        }
        if(!$item->count()){
            return response()->json([
                'status' => false,
                'msg' => 'این رویداد قبلا حذف شده !'
            ]);
        }
        $item->first()->delete();
        return response()->json([
            'status' => true,
            'msg' => 'با موفقیت حذف شد !'
        ]);
    }

    public function shouldPay()
    {
        if(!user()->should_pay){
            return redirect()->route('dashboard');
        }
        return view('Others.shouldPay');
    }

    public function pay()
    {
        if(!user()->should_pay){
            return redirect()->route('dashboard');
        }
        $MerchantID = option('payment_merchant');
        $Amount = intval(option('amount'));
        $Description = 'پرداخت حق اشتراک توسط ' . user()->name; // Required
        $Email = user()->email; // Optional
        $CallbackURL = route('pay.verify'); // Required

        $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $client->PaymentRequest(
            [
                'MerchantID' => $MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'CallbackURL' => $CallbackURL,
            ]
        );
        if ($result->Status == 100) {
            return redirect('https://www.zarinpal.com/pg/StartPay/'.$result->Authority.'/ZarinGate');
        } else {
            alert()->error('خطا در عملیات !','مشکلی در سیستم پرداخت به وجود آمده , لطفا با پشتیبانی تماس بگیرید !');
            return back();
        }
    }

    public function verifyPayment(Request $request)
    {
        if(!user()->should_pay){
            return redirect()->route('dashboard');
        }
        if(!$request->has('Status')){
            return redirect()->route('dashboard');
        }
        $MerchantID = option('payment_merchant');
        $Amount = intval(option('amount'));
        $Authority = $request->Authority;

        if ($request->Status == 'OK') {

            $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => $MerchantID,
                    'Authority' => $Authority,
                    'Amount' => $Amount,
                ]
            );

            if ($result->Status == 100) {
                user()->update([
                    'paid' => true,
                    'paid_amount' => $Amount,
                    'paid_ref' => $result->RefID,
                    'should_pay' => false
                ]);
                alert()->success('پرداخت موفق !','کد رهگیری پرداخت : ' . $result->RefID . ' , حال میتوانید از امکانات سایت استفاده کنید !')->persistent(true);
                return redirect()->route('dashboard');
            } else {
                alert()->error('پرداخت نا موفق !',$result->Status)->persistent(true);
                return redirect()->route('shouldPay');
            }
        } else {
            alert()->error('پرداخت نا موفق !','پرداخت توسط شما لغو شد !')->persistent(true);
            return redirect()->route('shouldPay');
        }
    }

    public function export()
    {
        return view('Others.export');
    }

    public function exportPost(Request $request)
    {
        return (new NumbersExport(intval($request->id)))->download('numbers.xlsx');
    }

    public function users()
    {
        return view('Others.users');
    }

    public function resetPass(Request $request)
    {
        if($request->phone || $request->code){
            if($request->op == 'sendCode'){
                if(cache()->has('phone.'.$request->ip())){
                    return response()->json([
                        'status' => false,
                        'msg' => 'برای تلاش مجدد باید حداقل 1 دقیقه صبر کنید !'
                    ]);
                }elseif(!User::wherePhone($request->phone)->count()){
                    return response()->json([
                        'status' => false,
                        'msg' => 'این شماره در وبسایت ثبت نشده !'
                    ]);
                }else {
                    $code = round(rand(10000,50000));
                    cache()->put('phone.'.$request->ip(),$code,now()->addMinutes(1));
                    cache()->put('phone.'.$request->ip().'.number',$request->phone,now()->addMinutes(2));
                    $res = send_pattern($request->phone,246,['code'=>$code,'app_name'=>'فراز اسمس']);
                    if($res){
                        return response()->json([
                            'status' => true,
                            'msg' => 'کد با موفقیت ارسال شد !'
                        ]);
                    }else {
                        return response()->json([
                            'status' => false,
                            'msg' => 'خطایی در سیستم پیش آمده , لطفا با پشتیبان سایت تماس بگیرید !'
                        ]);
                    }
                }
            }elseif($request->op == 'verifyPhone') {
                if(cache()->has('phone.'.$request->ip())){
                    $code = cache('phone.'.$request->ip());
                    if($request->code == $code){
                        $phone = cache('phone.'.$request->ip().'.number');
                        $user = User::wherePhone($phone)->first();
                        if($user){
                            $pass = floor(rand(1000000,9999999));
                            $user->update([
                                'password' => bcrypt($pass)
                            ]);
                            $res = send_pattern($phone,337,['password'=>$pass,'sitename'=>'فراز اسمس']);
                            if($res){
                                return response()->json([
                                    'status' => true,
                                    'msg' => 'کلمه عبور جدید برای شما ارسال خواهد شد !',
                                    'phone' => $phone
                                ]);
                            }else {
                                return response()->json([
                                    'status' => false,
                                    'msg' => 'خطایی در سیستم پیش آمده , لطفا مجددا تلاش کنید !',
                                ]);
                            }
                        }else {
                            return response()->json([
                                'status' => false,
                                'msg' => 'خطایی در سیستم پیش آمده , لطفا مجددا تلاش کنید !',
                            ]);
                        }
                    }else {
                        return response()->json([
                            'status' => false,
                            'msg' => 'کد مورد نظر نادرست است , لطفا دوباره تلاش کنید !',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'msg' => 'کد مورد نظر منقضی شده , لطفا مجددا تلاش فرمایید !'
                    ]);
                }
            }else {
                return response()->json([
                    'status' => false,
                    'msg' => 'درخواست نا معتبر !'
                ]);
            }
        }else {
            return response()->json([
                'status' => false,
                'msg' => 'ورود شماره تلفن ضروری است !'
            ]);
        }
    }
}
