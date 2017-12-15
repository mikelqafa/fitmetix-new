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

    private $req_id;

    private $sale_id;

    public function __construct()
    {
         $this->req_id = '734389    7';
    }

    /**

     * Show the application paywith paypalpage.

     *

     * @return \Illuminate\Http\Response

     */

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

    	$paypal_conf = \Config::get('paypal');

        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']),$this->req_id);

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

                return Redirect::route('events');

                /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/

                /** $err_data = json_decode($ex->getData(), true); **/

                /** exit; **/

            } else {

                \Session::put('error','Some error occur, sorry for inconvenience');

                return Redirect::route('events');

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

        return Redirect::route('events');

    }

    public function getPaymentStatus(Request $request)

    {

        /** Get the payment ID before session clear **/

        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/

        Session::forget('paypal_payment_id');
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']),$this->req_id);

        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();

        $execution->setPayerId($_GET['PayerID']);

        /**Execute the payment **/

        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') { 

            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $sale = $relatedResources[0]->getSale();
            Session::put('sale_id',$sale->getId());


            Session::put('success','Payment success');

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

            app('App\Http\Controllers\TimeLineController')->joiningPaidEvent();

        }

        Session::put('error','Payment failed');

        return Redirect::route('events');

    }

     public function refundPayment(Request $request) {
        $amt = new Amount();
        $amt->setTotal($request->amount)->setCurrency('USD');
        $refund = new Refund();
        $refund->setAmount($amt);
        $sale = new Sale();
        $sale->setId($request->sale_id);
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']),$this->req_id);
        try {
            $refundedSale = $sale->refund($refund, $this->_api_context);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }

        Session::put('success','Refund success');

        return Redirect::back();
    }

  }
