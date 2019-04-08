<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Library\Api;
use App\Models\GpfShipmentHandlingCharge;
use App\Models\Shipment;
use App\Models\Invoice;
use App\Models\ShipmentItem;
use App\Models\User;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\NewsSubscription;
use App\Http\Requests\Front\SubscriptionRequest;
use App\Transformers\PDFTransformer;
use App\Transformers\PersonalShopperPDFTransformer;
use DB;
use SEO;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];
        //Get list of country
        $data['country'] = Country::orderBy('name')->pluck('name', 'short_code');
        SEO::setTitle('Global Parcel Forward : Shop UK, Forward Parcels Globally');
        SEO::setDescription('Sign up & get a Global Parcel Forward free UK address! Shop your favourite UK brands and ship home hassle free. Join the #1 international shipping &amp; consolidation company today! ');
        SEO::opengraph()->setUrl(url('/'));
        SEO::setCanonical(url('/'));
        return view('frontend.pages.home', $data);
    }

    public function getPdf($id)
    {
        $shipment = Shipment::leftJoin('shipment_histories',function($join){
                    $join->on('shipment_histories.shipment_id','=','shipments.id')
                      ->where('shipment_histories.status_id','2');
                    })
                    ->leftJoin('users','users.id','=','shipment_histories.user_id')
                    ->select('shipments.*',DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
                    ->where('shipments.id',$id)
                    ->first();
        $invoice = Invoice::where('entity_id',$id)->where('entity_type',1)->first();
        $data['user'] = User::leftJoin('countries','countries.id','=','users.cd_country')->select('users.*','countries.name as country')->find($shipment->user_id);
        $data['shipment'] = PDFTransformer::transform($shipment);
        $data['shipment']['invoice_number'] = $invoice['invoice_number'];
        $data['shipment']['due_date'] = date('M d Y',strtotime($invoice['due_date']));
        $data['shipment']['created_at'] = date('M d Y',strtotime($invoice['created_at']));
        if(request('rst') == "json"){
          return $data;
        }
        return view('frontend.pages.pdf',$data);
    }

    public function getPersonalShopperPdf($id)
    {
        $quotation = Quotation::leftJoin('quotation_items','quotation_items.quotation_id','=','quotations.id')
                    ->select('quotations.*', DB::raw('SUM(quotation_items.admin_price) AS total'))
                    ->where('quotations.id',$id)
                    ->groupBy('quotations.id')
                    ->first();
        $quotation->items = QuotationItem::where('quotation_items.quotation_id',$id)->get();
        $invoice = Invoice::where('entity_id',$id)->where('entity_type',2)->first();
        $data['user'] = User::leftJoin('countries','countries.id','=','users.cd_country')->select('users.*','countries.name as country')->find($quotation->user_id);
        $data['quotation'] = PersonalShopperPDFTransformer::transform($quotation);
        $data['quotation']['invoice_number'] = $invoice['invoice_number'];
        $data['quotation']['due_date'] = date('M d Y',strtotime($invoice['due_date']));
        $data['quotation']['created_at'] = date('M d Y',strtotime($invoice['created_at']));
        if(request('rst') == "json"){
          return $data;
        }
        return view('frontend.pages.personal_shopper_pdf',$data);
    }

    public function membershipPdf($id)
    {
        $createdAt = '';
        $invoice = Invoice::find($id);
        $data['user'] = User::leftJoin('countries','countries.id','=','users.cd_country')->select('users.*','countries.name as country')->find($invoice->user_id);
        $invoice['created_date'] = $invoice['created_at'] ? date('M d Y',strtotime($invoice['created_at'])): NULL;
        $data['invoice'] = $invoice;
        return view('frontend.pages.membership_pdf',$data);
    }

    public function getServiceCharge()
    {
      return GpfShipmentHandlingCharge::where('start_price','<=',request('price'))
                  ->where('end_price','>=',request('price'))
                  ->pluck('gpf_price','id');
    }


    public function pricing()
    {
        $data = [];
        //Get list of country
		SEO::setTitle('Quick Estimate');
		SEO::setDescription('Get quick estimates, pricing and cost of packages and shipments from your globalparcelforward warehouse to your destinations worldwide');
		SEO::opengraph()->setUrl(url('/pricing'));
		SEO::setCanonical(url('/pricing'));
        $data['country'] = Country::orderBy('name')->pluck('name', 'short_code');

        return view('frontend.pages.pricing', $data);
    }


    public function signin()
    {
        return view('frontend.pages.signin');
    }

    //membership

    public function payment()
    {
        return view('frontend.pages.payment');
    }

    //dashboard

    public function dashboardHistory()
    {
        return view('frontend.pages.dashboard-history');
    }



    //blog
    public function blog()
    {
		    SEO::setTitle('Shop the Best from the UK : GPF International Shipping');
        SEO::setDescription('Globalparcelforward brings your favourite UK brands to you. See the latest deals and learn how you can save by shopping UK stores and shipping internationally with Global Parcel Forward.');
        SEO::opengraph()->setUrl(url('/blog'));
        SEO::setCanonical(url('/blog'));
        return view('frontend.pages.blog');
    }

    public function blogDetail()
    {
        return view('frontend.pages.blog-detail');
    }

    //common
    public function faqs()
    {
		SEO::setTitle('International Shipping Questions : GPF FAQs');
		SEO::setDescription('Questions about international shipping rates? Want to shop UK stores and ship abroad? See how GlobalParcelForward can save you up to 70% on shipping.');
		SEO::opengraph()->setUrl(url('/faqs'));
		SEO::setCanonical(url('/faqs'));
        return view('frontend.pages.faqs');
    }

    public function privacy()
    {
		SEO::setTitle('Privacy & Cookie Policy in Using Our Website');
		SEO::setDescription('Privacy & cookie policy information about using Global Parcel Forward international shipping services and its web properties.');
		SEO::opengraph()->setUrl(url('/privacy-policy'));
		SEO::setCanonical(url('/privacy-policy'));
        return view('frontend.pages.privacy');
    }

    public function services()
    {
		SEO::setTitle('GPF International Shipping : Our Services');
        SEO::setDescription('Global Parcel Forward services includes: Shopping, Freight forwarding &amp; consolidation services. Get a free UK address today.');
        SEO::opengraph()->setUrl(url('/services'));
        SEO::setCanonical(url('/services'));
        return view('frontend.pages.services');
    }

    public function legalTerms()
    {
        return view('frontend.pages.legal-terms');
    }

    public function termsTrade()
    {
		SEO::setTitle('Terms of Trade in Using Our Website');
        SEO::setDescription('Terms of trade information about using Global Parcel Forward international shipping services and its web properties.');
        SEO::opengraph()->setUrl(url('/terms-trade'));
        SEO::setCanonical(url('/terms-trade'));
        return view('frontend.pages.terms_trade');
    }

    public function returnPolicy()
    {
		SEO::setTitle('Return Policies in Using Our Website');
		SEO::setDescription('Return policy information about using Global Parcel Forward international shipping services and its web properties.');
		SEO::opengraph()->setUrl(url('/return-policy'));
		SEO::setCanonical(url('/return-policy'));
        return view('frontend.pages.return_policy');
    }

    public function sitemap()
    {
		SEO::setTitle('Sitemap');
		SEO::opengraph()->setUrl(url('/sitemap'));
		SEO::setCanonical(url('/sitemap'));
        return view('frontend.pages.sitemap');
    }

    public function subscribe(SubscriptionRequest $request)
    {
      NewsSubscription::create($request['email']);
      $data['data'] = [];

      return Api::ApiResponse($data);
    }
}
