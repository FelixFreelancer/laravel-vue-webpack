<html>

<head>
    <title>GPF - Invoice PDF</title>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
<style>
    body {
        margin: 0;
        padding: 0;
        background: #525659;
        padding: 20px 0;
        font-family: 'Open Sans', sans-serif;
    }
    .invoice{
        padding:10px;
        text-align: center;
        border:2px solid #dadada;
        margin-left:-3px;
        background: #dadada;
    }
    .invoice label{
        font-size:12px;
        text-transform: uppercase;
        margin:0;
        letter-spacing: 2px;
        display: block;
        color:#0d476b;
        font-weight:600;
    }
    page {
        display: block;
        margin: 0 auto;
        box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        background-color: #fff;
        -webkit-print-color-adjust: exact;
    }

    page[size="A4"] {
        width: 21cm;
        /* min-height: 29.7cm; */
    }

    .content{
        padding:30px;
    }
    .invoice-logo{
        width:200px;
    }
    .table-header{
        width:100%;
    }
    .table-header tr td{
        width: 5%;
    }
    .table-header tr td:first-child{
        width: 60%;
    }
    .c-info{
        margin:0;
        padding:0;
        list-style: none;
    }
    .c-info tr td a{
        text-decoration:none;
        color:#888;
    }
    .c-info tr td img{
        opacity:0.8;
        width:20px;
    }
    .c-info tr td {
        font-size:13px;
        margin-left:10px;
        text-align: left;
        vertical-align: top;
        padding:2px 0;
        color:#888;
    }
    .c-info tr td:first-child{
        width:30px;
    }
    .c-info tr td i{
        font-size:20px;
    }
    .vr-top{
        vertical-align: top;
    }
    .vr-bottom{
        vertical-align: bottom;
    }
    .content__info{
        margin-top:30px;
    }
    .content__info ul{
        margin:0;
        padding:0;
        list-style: none;
    }
    .content__info table{
        width:100%;
        margin-top:-5px;
    }

    .content__right{
        display: flex;
        justify-content: flex-end;
    }
    .content-important{
        width:100%;
        border-spacing: 0px;
        border:1px solid #dadada;
    }

    .content-important tr td{
        padding:10px 20px;
        border:1px solid #dadada;
        text-align: center;
    }
    .content-important tr td:nth-child(1){
        background: #dadada;
    }
    .content-important tr td:nth-child(3){
        background: #0d476b;
    }
    .content-important tr td:nth-child(3) label{
        color:#fff;
    }
    .content-important tr td:nth-child(3) span{
        color:#fff;
        font-size:18px;
        font-weight:600;
    }
    .content-important tr label{
        font-size:12px;
        letter-spacing: 2px;
        display: block;
        text-align: center;
        color:#0d476b;
        font-weight: 600;
    }
    /* .content-important >li span{
        color:#0d476b;
    } */
    .invoice-table{
        border-collapse: collapse;
        width:100%;
        border:2px solid #dadada;
    }
    .invoice-table thead{
        border-bottom:2px solid #dadada;
    }
    .invoice-table tr th:first-child{
        text-align: left;
    }
    .content__left >ul >li{
        font-size:14px;
    }
    .content__left >ul >li:first-child{
        font-weight:600;
    }
    .invoice-table tr th{
        color:#0d476b;
        text-transform: uppercase;
        font-weight:600;
        font-size: 14px;
        letter-spacing: 2px;
        padding:10px;
    }
    .invoice-table tr td{
        padding:10px;
        font-size:14px;
        text-align: center;
    }
    .invoice-table tr td:first-child{
        text-align: left;
    }
    .invoice-table tr + tr{
        border-top:1px solid #dadada;
    }
    .invoice-table tbody{
        border-bottom:2px solid #dadada;
    }
    .invoice-table tfoot tr td:nth-child(1){
        font-size: 14px;
        text-transform: capitalize;
        font-weight: 600;
        color: #0d476b;
        letter-spacing: 2px;
    }
    .invoice-table tfoot tr td:nth-child(2) span{
        font-weight: 600;
        font-size:16px;
        color:#000;
    }
    .content__block{
        margin-top:60px;
    }
    .invoice-table tr.dark-border{
        border-top:2px solid #dadada;
    }
    .total{
        font-weight:600;
    }
    .due-total{
        font-weight:700;
        color:#0d476b;
    }
    .content__footer{
        margin-top:200px;
    }
    .content__footer p{
        font-size:14px;
        text-align: center;
    }
    .table-footer tr td{
        width:50%;
        padding:5px;
    }
    .table-footer tr td p{
        font-size:12px;
        margin:0px;
    }
    .table-footer tr td p label{
        font-weight:600;
        text-transform: uppercase;
        font-size:12px;
        margin-right:5px;
        color:#0d476b;
    }
    .invoice-success{
        font-size:13px;
        padding:30px 10px 10px;
        border-top:1px solid #0d476b;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight:600;
        margin-top:20px;
        text-align:center;
    }
    @media print {
        body {
            background:#fff;
            font-family: 'Open Sans', sans-serif;
            /* margin-top: 2cm;
            margin-right: 2cm;
            margin-bottom: 1.5cm;
            margin-left: 2cm; */
        }
        page[size="A4"] {
            width: 100%;
            /* min-height: 29.7cm; */
        }
        page {
            display: block;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: none;
            -webkit-print-color-adjust: exact;
        }
        .table-break{
            page-break-before:always;
        }
        table{
            overflow: visible !important;
        }
        tr { page-break-inside: avoid }
    }
</style>
</head>
<body>
    <page size="A4">
        <div class="content">
            <div class="content__header">
                <table class="table-header">
                    <tr>
                        <td class="vr-top">
                            <img src="{!! asset('img/logo.jpg') !!}" alt="" class="invoice-logo">
                        </td>
                        <td>
                            <table class="c-info">
                                <tr>
                                    <td><img src="{!! asset('img/location.svg') !!}" alt=""></td>
                                    <td>
                                        Unit 1, Finishing house, Peel Street<br>
                                        Willenhall, West Midlands<br>
                                        WV13 2BZ, United Kingdom
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="{!! asset('img/email.svg') !!}" alt=""></td>
                                    <td>
                                        <a href="mailto:support@globalparcelforward.com">
                                            support@globalparcelforward.com
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="{!! asset('img/phone.svg') !!}" alt=""></td>
                                    <td>
                                        <a href="tel:+44 1 902 916 032">
                                            +44 1 902 916 032
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="content__info">
                <table class="content-important">
                    <tr>
                        <td>
                            <label>INVOICE ID</label>
                            <span>#{!! $invoice['invoice_number'] !!}</span>
                        </td>
                        <td>
                            <label for="">INVOICE DATE</label>
                            <span>{!! $invoice['created_date'] !!}</span>
                        </td>
                        <td>
                            <label for="">PLEASE PAY</label>
                            <span>{!! config('site.default_currency').$invoice['total'] !!} </span>
                        </td>
                    </tr>
                </table>
                <table style="margin-top:10px;">
                    <tr>
                        <td width="60%" style="vertical-align:top;">
                            <div class="content__left">
                                <ul>
                                    <li>{!! $user['first_name']." ".$user['last_name'] !!}</li>
                                    <li>{!! $user['cd_address']."<br>".$user['cd_city'].", ".$user['cd_postalcode']."<br>".$user['cd_state'].", ".ucfirst(strtolower($user['country']))."<br>+".$user['cd_country_code']." ".$user['cd_phone'] !!}</li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <ul style="text-align:right;">
                                <li><img src="{!! asset('img/paid.png') !!}" width="40%"</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="content__block">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monthly Subscription For {!! date('d-m-Y')." to ".date('d-m-Y',strtotime('+'.config('site.membership_validity').' months')) !!}</td>
                            <td>{!! config('site.default_currency').$invoice['total'] !!} </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><label>TOTAL</label></td>
                            <td><span>{!! config('site.default_currency').$invoice['total'] !!} </span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="content__footer">
                <p>
                    You can find answers to common questions here. And you can always reach us at support@globalparcelforward.com GlobalParcelForward is a trading name of Rehoboth Business Solutions Limited, a company registered in England and Wales with registered number 10246097. Our registered office is at Unit 1, Finishing House, Peel Street, Willenhall, West Midlands, WV13 2BZ.
                </p>
                <div class="invoice-success">
                    Thank you for choosing <a href="https://globalparcelforward.com/" target="_blank">GlobalParcelForward.com</a>
                </div>
            </div>
        </div>
    </page>
</body>

</html>
