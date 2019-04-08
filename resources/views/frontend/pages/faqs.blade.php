@extends('frontend.layouts.common')

@section('contentHeader')

@stop

@section('content')
    <div class="page-cover">
        <h3 class="page-cover__title">FAQs</h3>
    </div>
    <div class="ss-container">
        <div class="content-wrap">
            <div class="faq">
                <div class="faq__note">
                    <p>Get all the answers to the most frequently asked questions (FAQs)
                       regarding membership, shipping, personal shopping services, payments and much more.
                    </p>
                </div>
                <div class="faq__accordian ss-accordian">
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-0" type="checkbox" name="tabs1"/>
                            <label for="tab-0">How can I apply for a membership?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p><a href="{!! url('/registration/plan') !!}" target="_blank" title="Pricing & Membership">Click here</a> to apply for membership. You will immediately receive a free Globalparcelforward address you can use to shop UK online retailers.
                                   <br><br>
                                   View and compare our membership options to decide which is best for you. The GPF Premium membership offers the best value, with discounted shipping costs, complimentary consolidation, 30 days of complimentary storage and even more.</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-1" type="checkbox" name="tabs1"/>
                            <label for="tab-1">How do I use my GPF address?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p>Enter your GPF address as the "Shipping Address" as shown below, whenever you buy from any UK online stores.
                                  <br><br><b>Your Name
                                  <br>  Unit 1 Finishing House, Peel Street,
                                  <br>  Suite UKxxxxxx, GPF-xxxxxxx-UK
                                  <br>  Willenhall, West Midlands WV13 2BZ
                                  <br>  United Kingdom </b>
                                  <br><br>  To find your GPF address, sign in to your account, and it will be in the upper right hand corner.
                                  <br><br>  You may also be asked for a “billing address" when paying online retailers. Use the home country address associated with your credit card.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-2" type="checkbox" name="tabs1"/>
                            <label for="tab-2">Why was my registration unsuccessful?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p> If you are having difficulty registering for a GPF membership, please follow these steps:
                                    <br>
                                    <br>
                                      1.  Clear the cookies on your Web browser.<br>
                                      2.  Make sure you are entering a proper card information that has an expiration date of next month or later if you are registering for a premium account. Credit cards expiring this month cannot be accepted.<br>
                                      3.  Make sure your Web browser is up to date. Out of date browsers may have functionality issues. We recommend Google Chrome, Firefox or Safari.<br>
                                    <br>
                                    If you continue to experience problems, please submit a ticket here or chat live with one of our customer representatives. Please include the following information:<br>
                                    <br>
                                      1.  Your full name<br>
                                      2.  Type of Web browser are you using<br>
                                      3.  Country you are applying from.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-3" type="checkbox" name="tabs1"/>
                            <label for="tab-3">How does my GPF online account work?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p><b>Sign in to your GPF account to:</b>
                                   <br>
                                   <br>
                                    1. Check if your merchandise has been received. It will be in your warehouse.<br>
                                    2. Create a ship request<br>
                                    3. Check the status of your shipments<br>
                                    4. Change any of your account details such as credit card, shipping address and membership information.<br><br>
                                    <b>Note:</b>You will receive an email notification when you receive a package, or when GPF sends a shipment to you.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-4" type="checkbox" name="tabs1"/>
                            <label for="tab-4">Can I start using GPF services immediately after I sign up?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p> <b>Yes.</b> Once you receive your membership confirmation, you are ready to start shopping! GPF will immediately begin accepting your merchandise and, we will immediately accept your pervels.
                                    <br><br>Please ensure all your shipments include the address of your personal GPF address including your unique suite number. Any packages shipped to GPF with an incomplete or inaccurate address might be delayed.
                                    <br><br>Packages requiring review for incomplete or incorrect addresses will incur a £5 per package special handling fee.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-5" type="checkbox" name="tabs1"/>
                            <label for="tab-5">How do I cancel my GPF account?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p>
                                  We're sorry to hear you would like to cancel your membership, and will work with you to resolve any issues you may be experiencing.
                                  <br><br>You may consider switching from a Premium to a Free membership, which has no monthly membership fee, but still gives you access to our discounted rates, UK shopping tips and savings advice. To change your membership from a Premium to Free:<br><br>
                                  1. Sign in to your account. From your dashboard<br>
                                  2. Go to <b>Subscription Plan</b> > <b>Membership</b> tab<br>
                                  3. Click Downgrade Plan next to <b>Your subscribed plan</b><br>
                                  4. Click <b>Submit</b> to save your changes
                                  <br><br>Please let us know what we can do to resolve any issues you might be facing. <a target="_blank" rel="noopener noreferrer" href="https://globalparcelforward.freshdesk.com/support/tickets/new" title="Submit a Ticket">Please submit a ticket</a> and we look forward to working with you to resolve your concerns.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-6" type="checkbox" name="tabs1"/>
                            <label for="tab-6">I understand you will give me a UK address. Is it a P.O. box address?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p><b>No.</b> GPF provides you with a complete street address and unique suite number, not a P.O. box.
                                  <br><br>Sample GPF Address:
                                  <br><br><b>Your Name
                                  <br>  Unit 1 Finishing House, Peel Street,
                                  <br>  Suite UKxxxxxx, GPF-xxxxxxx-UK
                                  <br>  Willenhall, West Midlands WV13 2BZ
                                  <br>  United Kingdom</b>
                                  <br><br> All UK shipping companies will be able to deliver to your GPF (Globalparcelforward) address.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tab-wrap">
                            <input id="tab-7" type="checkbox" name="tabs1"/>
                            <label for="tab-7">Why is my login information not working?</label>
                            <div class="tab-arrow"><i class="fas fa-angle-down"></i></div>
                            <div class="tab-content">
                                <p>
                                <ul>
                                  <br>
                                  <li>Make sure your Caps Lock button is not turned on.</li>
                                  <li>Make sure your Web browser is updated to the latest version. We prefer Chrome, Firefox and Safari.</li>
                                  <li>Make sure your browser is accepting cookies.</li>
                                  <li>If you are still experiencing problems, <a target="_blank" rel="noopener noreferrer" href="https://globalparcelforward.freshdesk.com/support/tickets/new" title="Submit a Ticket">Please submit a ticket here</a>.</li>
                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="faq__note faq__note-bottom">
                  <p>
                    Can't find your answers?
                    <a href="https://globalparcelforward.freshdesk.com" target="_blank" title="Helpdesk">
                      <button class="button button--primary">Search Here</button>
                    </a>
                  </p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('contentFooter')

@stop
