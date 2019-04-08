@extends('frontend.layouts.common')

@section('contentHeader')

@stop

@section('content')
    <div class="page-cover">
        <h1 class="page-cover__title">Shopping, Freight forwarding & Consolidation Services</h1>
    </div>
    @include('frontend.partials.privacy_cookie')

    <div class="services">
        <div class="services__wrap">
            <div class="services__image services--image1"></div>
            <div class="services__content">
                <div class="services__box">
                    <h2 class="services__title">Shopping Service</h2>
                    <p class="services__text">Looking for the cheapest UK parcel forwarding service provider, because you are having difficulties buying from a UK store or auction site due to payment methods, verifications or country restrictions? We can make the purchase for you. For many reasons, some UK/European retailers do not allow customers outside of the United Kingdom to shop on their website, refusing to accept a non-UK or non-EU credit card. Global Parcel Forward offers a Personal Shopper, otherwise known as a UK “shop for me” service, that allows our members to shop from restricted sites within the UK/EU. How does this work?
                    </p>
                    <ul class="ul-reset services__list">
                        <li>
                            <h3>Tell us about your order</h3>
                            <p>Tell us about your order by completing a request form from your dashboard. Our system allows you to input the detail of your purchases including sizes, colors, and quantities from whichever retailer you desire. Just be sure to provide a direct link and you’re on!</p>
                        </li>
                        <li>
                            <h3>Receive a quote</h3>
                            <p>Once we receive your request, we verify the product details including descriptions, prices, colors, quantity and we send you a customized quote which includes the cost of your items, our personal shopper service/administration fee, any costs for shipping to our storage facility in the UK (your personal warehouse address) and any applicable VAT.</p>
                        </li>
                        <li>
                            <h3>Pay for your items</h3>
                            <p>Complete payment for your items using your card. We accept all popular cards from almost all countries.</p>
                        </li>
                        <li>
                            <h3>We shop for you</h3>
                            <p>Once payment is confirmed, Global Parcel Forward will place your orders with retailers and suppliers on your behalf using our cards and you will use our free parcel storage service, to have it delivered to your UK warehouse address. Please note that reshipping service fees from GPF’s UK warehouse to your destination address will be invoiced separately once the goods have been received at your free UK warehouse address. Our forwarding service is also the best and the most competitive you will find among all the UK forwarding service providers.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="services__wrap services__wrap2">
            <div class="services__content">
                <div class="services__box">
                    <h2 class="services__title">Our Warehouse</h2>
                    <ul class="ul-reset services__list">
                        <li>
                            <h3>Storage Service</h3>
                            <p>By taking advantage of our free storage service in the UK, your purchases will be securely housed in our warehouse facility, allowing time for multiple package delivery and maximum consolidation savings. Please note that a daily charge will apply for storage after 14 days for FREE members and 30 days for PREMIUM members if you have not arranged the shipping of your goods that have been stored in our warehouse for over this stipulated period of time.
                            <br><br>
                                We also offer secure storage of large and irregular items that fall outside our standard pricing structure for our shipping forwarding service, or parcels we traditionally help our customers forward globally at competitive rates. All you need to do to take advantage of this our UK storage service is just contact us to check the availability of storage space, and agree to storage fees, prior to arranging the delivery of your large and irregular sized items.
                                 <a href="{!! url('/contact-us') !!}" target="_blank" title="Contact Global Parcel Forward">Click here</a> for further information about our large Item storage. If you also need a UK reshipper to help with shipping these items to you at an international destination, you should let us know, so we can liaise with our trusted network of couriers and freight forwarders to get you the best price.
                                To speak to a member of our customer service team, <a href="https://tawk.to/globalparcelforward" target="_blank" title="Contact Global Parcel Forward">click here</a>.

                            </p>
                        </li>
                        <li>
                            <h3>Return Service</h3>
                            <p>If for some reasons, you want to return an item that is still in your warehouse, we are happy
                              to process a return for a fee.</p>
                        </li>
                        <li>
                            <h3>Consolidation Service</h3>
                            <p>Consolidating is the easiest way to save on international shipping. A GPF shipping expert repackages all your items to fit in one conveniently sized package which dramatically reduces delivery fee even when you shop from multiple stores.</p>
                        </li>
                        <li>
                            <h3>Package Disposal Service</h3>
                            <p>If for some reasons, you no longer want your items or do not pay for the shipping or extra storage costs, we will assume the packages are no longer needed and will be happy to dispose them for you.</p>
                        </li>
                        <li>
                            <h3>Package Inspection Service</h3>
                            <p>For a fee, you can use the service of one of our UK shipping forwarders, who can carefully inspect your packages before they are shipped from the UK. This service is particularly important for buyers that want to make sure their purchase is a good deal. If you are buying a car, bidding for a machinery or even planning on buying a wedding dress and want someone to physically inspect the item before purchase, you’ve found Global Parcel Forward.</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="services__image services--image2"></div>
        </div>
        <div class="services__wrap">
            <div class="services__image services--image3"></div>
            <div class="services__content">
                <div class="services__box">
                    <h2 class="services__title">Shipping Service</h2>
                    <p class="services__text">We offer household and large shipment forwarding services including household furniture, large appliances, cars, auto parts, bikes, garden equipment including swings, trampolines and lawn mowers and lots of other large items using euro pallets or containers via road freight, sea freight, and air freight.
                    </p>
                    <ul class="ul-reset services__list">
                        <li>
                            <h3>Bulky Items</h3>
                            <p>Where items are bulky and outside our standard courier shipping parameters, we will at your request, work with haulers, freight forwarders, sea freight, pallet, and container shipping companies to provide you with the most cost-effective shipping methods and services.</p>
                        </li>
                        <li>
                            <h3>Reduced Cost</h3>
                            <p>If shipping bulky items like the ones mentioned above, you may also be happy to ship with a longer transit time to reduce costs.</p>
                        </li>
                        <li>
                            <h3>Talk to us</h3>
                            <p>To get the best and cheapest storage and parcel forwarding service from the UK, please contact our customer services team at <a href="mailto:ssupport@globalparcelforward.com" target="_top" title="Email Us">support@globalparcelforward.com</a> prior ordering large
                              items, or simply choose and register for a membership type that suits you most by following this <a href="{!! url('/registration/plan') !!}" target="_top" title="Register On GPF">link.</a> Global Parcel Forward is indeed the cheapest UK package forwarding service provider that you can always count on.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('contentFooter')

@stop
