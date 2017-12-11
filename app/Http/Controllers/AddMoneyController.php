<?php

namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\User;

use Validator;

use URL;

use Session;

use Redirect;

use Input;

/** All Paypal Details class **/

use PayPal\Rest\ApiContext;

use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Amount;

use PayPal\Api\Details;

use PayPal\Api\Item;

use PayPal\Api\ItemList;

use PayPal\Api\Payer;

use PayPal\Api\Payment;

use PayPal\Api\RedirectUrls;

use PayPal\Api\ExecutePayment;

use PayPal\Api\PaymentExecution;

use PayPal\Api\Transaction;

class AddMoneyController extends Controller

{

    private $_api_context;

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    /**

     * Show the application paywith paypalpage.

     *

     * @return \Illuminate\Http\Response

     */

    public function payWithPaypal()

    {

        return view('paywithpaypal');

    }

    /**

     * Store a details of payment with paypal.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function postPaymentWithpaypal(Request $request)

    {
        $request->session()->put('event_timeline_id', $request->timeline_id);
        
        $request->session()->put('amount', $request->amount);

        $user_id = Auth::user()->id;

        $request->session()->put('user_id', $user_id);

    	$paypal_conf = \Config::get('paypal');

        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));

        $paypal_conf['settings']['currency'] = $request->currency;

        $this->_api_context->setConfig($paypal_conf['settings']);

        $payer = new Payer();

        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Item 1') /** item name **/
            ->setCurrency($paypal_conf['settings']['currency'])

            ->setQuantity(1)

            ->setPrice($request->get('amount')); /** unit price **/

        $item_list = new ItemList();

        $item_list->setItems(array($item_1));

        $amount = new Amount();

        $amount->setCurrency($paypal_conf['settings']['currency'])

            ->setTotal($request->get('amount'));

        $transaction = new Transaction();

        $transaction->setAmount($amount)

            ->setItemList($item_list)

            ->setDescription('Your transaction description');

        $redirect_urls = new RedirectUrls();

        $redirect_urls->setReturnUrl(URL::route('payment.status')) /** Specify return URL **/

            ->setCancelUrl(URL::route('payment.status'));

        $payment = new Payment();

        $payment->setIntent('Sale')

            ->setPayer($payer)

            ->setRedirectUrls($redirect_urls)

            ->setTransactions(array($transaction));

            /** dd($payment->create($this->_api_context));exit; **/

        try {

            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {

                \Session::put('error','Connection timeout');

                return Redirect::route('addmoney.paywithpaypal');

                /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/

                /** $err_data = json_decode($ex->getData(), true); **/

                /** exit; **/

            } else {

                \Session::put('error','Some error occur, sorry for inconvenient');

                return Redirect::route('addmoney.paywithpaypal');

                /** die('Some error occur, sorry for inconvenient'); **/

            }

        }

        foreach($payment->getLinks() as $link) {

            if($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();

                break;

            }

        }

        /** add payment ID to session **/

        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {

            /** redirect to paypal **/

            return Redirect::away($redirect_url);

        }

        \Session::put('error','Unknown error occurred');

        return Redirect::route('addmoney.paywithpaypal');

    }

    public function getPaymentStatus(Request $request)

    {

        /** Get the payment ID before session clear **/

        $payment_id = $request->session()->get('paypal_payment_id');

        /** clear the session payment ID **/

        $request->session()->forget('paypal_payment_id');

        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            $request->session()->put('error','Payment failed');

            return Redirect::route('addmoney.paywithpaypal');

        }

        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();

        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/

        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') { 

            $request->session()->put('success','Payment success');

            $user_id = $request->session()->get('user_id');
            $balance = $request->session()->get('amount');

            $referer = Auth::user()->affiliate_id;

            $affiliate_balance = 0.1 * $balance;

            $scout = User::find($referer)->increment('balance',$affiliate_balance);
            
            $user = User::find($user_id);
            $spent = $user->custom_option2 + $balance;
            $spent = (string) $spent;
            $user->custom_option2 = $spent;
            $user->save();

            
            $request->session()->forget('user_id');
            $request->session()->forget('amount');

            // return Redirect::route('addmoney.paywithpaypal');
            app('App\Http\Controllers\TimeLineController')->joiningPaidEvent();

        }

        $request->session()->put('error','Payment failed');

        return Redirect::route('addmoney.paywithpaypal');

    }

  }
