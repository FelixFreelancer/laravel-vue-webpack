@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <form class="dashboard__content dashboard__content--pd card-shadow">
        <div class="rect"></div>
        <div class="invoice-wrap">
            <h3 class="dashboard__content__title">Invoices</h3>
            <ul class="ul-reset invoice__list">
                <li>
                    <div class="invoice__box">
                        <div class="invoice__header">
                            <div class="invoice__info">
                                <ul class="ul-reset invoice__maininfo">
                                    <li>
                                        <label>Date:</label><span>February 22th, 2018</span>
                                    </li>
                                    <li>
                                        <label>Invoice:</label><span>#87234687</span>
                                    </li>
                                </ul>
                                <div class="invoice__title">
                                    <label>Personal Shopping from BabyBear shop</label>
                                </div>
                            </div>
                            <div class="invoice__status"><a class="button paid" href="#nogo">PAID</a></div>
                        </div>
                        <div class="invoice__table__wrap">
                            <table class="invoice__table">
                                <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>RedCoral 2.0 IphoneX Case</td>
                                    <td>10</td>
                                    <td>£8</td>
                                    <td>£80.00</td>
                                </tr>
                                <tr>
                                    <td>Xiao Mi Y1 and CCTV Camera</td>
                                    <td>1</td>
                                    <td>£30</td>
                                    <td>£30.00</td>
                                </tr>
                                <tr>
                                    <td>Phillips LED Bulb Wireless</td>
                                    <td>5</td>
                                    <td>£10</td>
                                    <td>£50.00</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2">
                                        <label>Total:</label><span>£160.00</span>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="invoice__box">
                        <div class="invoice__header">
                            <div class="invoice__info">
                                <ul class="ul-reset invoice__maininfo">
                                    <li>
                                        <label>Date:</label><span>February 22th, 2018</span>
                                    </li>
                                    <li>
                                        <label>Invoice:</label><span>#87234687</span>
                                    </li>
                                </ul>
                                <div class="invoice__title">
                                    <label>Shipping Cost: DHL Express</label>
                                </div>
                            </div>
                            <div class="invoice__status"><a class="button paid" href="#nogo">PAID</a></div>
                        </div>
                        <div class="invoice__table__wrap">
                            <table class="invoice__table">
                                <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>DHL Express Regular Shipping Destination: Hongkong (20X10X15 cm, 1kg)</td>
                                    <td>1</td>
                                    <td>£125</td>
                                    <td>£125.00</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2">
                                        <label>Total:</label><span>£125.00</span>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
@stop

@section('contentFooter')

@stop