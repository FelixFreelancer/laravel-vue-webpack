<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SEO;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index()
    {
      $data['user'] = [];
      if(request('inquiry') && auth()->check()){
        $user = auth()->user();
        $data['user']['id'] = $user->id;
        $data['user']['name'] = $user->first_name." ".$user->last_name;
        $data['user']['email'] = $user->email;
        $data['user']['phone_no'] = $user->cd_phone;
        $data['user']['subject'] = "Inquiry for user verification";
      }
	  	SEO::setTitle('Contact Us : Call, Chat or Email Anytime');
		SEO::setDescription('Questions about shipping internationally with GlobalParcelForward? Need global shopping and logistics services? Talk with a Globalparcelforward representative today.');
		SEO::opengraph()->setUrl(url('/contact-us'));
		SEO::setCanonical(url('/contact-us'));
        return view('frontend.pages.contact-us.index',$data);
    }

    public function store(Request $request)
    {
        $this->validate(request(), ContactUs::validationRules());
		$request->request->add(['status'=>0]);
        $contactus = new ContactUs($request->all());

        if (!$contactus->save()) {
            session()->flash('class', 'danger');
            session()->flash('message', 'Something went wrong. Please try again later.');
        }
        try {
            Mail::to(config('site.contact_us_email'))->send(new \App\Mail\ContactUs($contactus));
        } catch (\Exception $e) {
            Log::info($e);
        }
        session()->flash('class', 'success');
        session()->flash('message', 'Contact Enquiry Saved Successfully.');
        return redirect('/thank-you');
    }
}
