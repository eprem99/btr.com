<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>{{ (is_null($estimate->estimate_number)) ? '#'.$estimate->id : $estimate->estimate_number }}</title>
    <style>
        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: normal;
            src: url("{{ asset('fonts/TH_Sarabun.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: bold;
            src: url("{{ asset('fonts/TH_SarabunBold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: italic;
            font-weight: bold;
            src: url("{{ asset('fonts/TH_SarabunBoldItalic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: italic;
            font-weight: bold;
            src: url("{{ asset('fonts/TH_SarabunItalic.ttf') }}") format('truetype');
        }


        @if(strtolower($global->locale) == 'zh-hk' || strtolower($global->locale) == 'zh-cn' || strtolower($global->locale) == 'zh-sg' ||
           strtolower($global->locale) == 'zh-tw' || strtolower($global->locale) == 'cn')
            @font-face {
            font-family: SimHei;
            /*font-style: normal;*/
            font-weight: bold;
            src: url('{{ asset('fonts/simhei.ttf') }}') format('truetype');
        }
        @endif

        @php
            $font = '';
            if(strtolower($global->locale) == 'ja') {
                $font = 'ipag';
            } else if(strtolower($global->locale) == 'hi') {
                $font = 'hindi';
            } else if(strtolower($global->locale) == 'th') {
                $font = 'THSarabun';
            }else if(strtolower($global->locale) == 'zh-hk' || strtolower($global->locale) == 'zh-cn' || strtolower($global->locale) == 'zh-sg' ||
            strtolower($global->locale) == 'zh-tw' || strtolower($global->locale) == 'cn') {
                $font = 'SimHei';
            } else {
                $font = 'noto-sans';
            }
        @endphp

        * {
            font-family: {{$font}}, DejaVu Sans , sans-serif;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 100%;
            height: auto;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-size: 14px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        h2 {
            font-weight:normal;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }



        #company {

        }

        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.2em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {

        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 5px 10px 7px 10px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3 {
            color: #767676;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #767676;
            width: 10%;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }


        table .total {
            background: #767676;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total
        {
            font-size: 1.2em;
            text-align: center;
        }

        table td.unit{
            width: 35%;
        }

        table td.desc{
            width: 45%;
        }

        table td.qty{
            width: 5%;
        }

        .status {
            margin-top: 15px;
            padding: 1px 8px 5px;
            font-size: 1.3em;
            width: 80px;
            color: #fff;
            float: right;
            text-align: center;
            display: inline-block;
        }

        .status.unpaid {
            background-color: #E7505A;
        }
        .status.paid {
            background-color: #26C281;
        }
        .status.cancelled {
            background-color: #95A5A6;
        }
        .status.error {
            background-color: #F4D03F;
        }

        table tr.tax .desc {
            text-align: right;
            color: #1BA39C;
        }
        table tr.discount .desc {
            text-align: right;
            color: #E43A45;
        }
        table tr.subtotal .desc {
            text-align: right;
            color: #1d0707;
        }
        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 10px 20px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-bottom: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }

        table.billing td {
            background-color: #fff;
        }

        table td div#invoiced_to {
            text-align: left;
        }

        #notes{
            color: #767676;
            font-size: 11px;
        }

        .item-summary{
            font-size: 12px;
        }

        /*.logo {*/
            /*text-align: right;*/
        /*}*/
        /*.logo img {*/
            /*max-width: 150px !important;*/
        /*}*/

        #logo {
            float: right;
            margin-top: 11px;
        }

        #logo img {
            height: 55px;
            margin-bottom: 15px;
        }

        .logo {
            text-align: right;
        }
        .logo img {
            max-width: 150px !important;
        }
        @if(strtolower($global->locale) == 'zh-hk' || strtolower($global->locale) == 'zh-cn' || strtolower($global->locale) == 'zh-sg' ||
            strtolower($global->locale) == 'zh-tw' || strtolower($global->locale) == 'cn')
            body
        {
            font-weight: normal !important;
        }
    </style>
</head>
<body>
<header class="clearfix">

    <table cellpadding="0" cellspacing="0" class="billing">
        <tr>
            <td>
                <div id="invoiced_to">
                    <small>@lang("app.client"):</small>
                    <h2 class="name">{{ ucwords($estimate->client->name) }}</h2>
                    <div>{!! nl2br($estimate->client->address) !!}</div>
                </div>
            </td>
            <td>
                <div id="company">
                    <div class="logo">
                        <img src="{{ invoice_setting()->logo_url }}" alt="home" class="dark-logo" />
                    </div>
                    <small>@lang("modules.invoices.generatedBy"):</small>
                    <h2 class="name">{{ ucwords($global->company_name) }}</h2>
                    @if(!is_null($settings))
                        <div>{!! nl2br($global->address) !!}</div>
                        <div>P: {{ $global->company_phone }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>
</header>
<main>
    <div id="details" class="clearfix">

        <div id="invoice">
            <h1>{{ (is_null($estimate->estimate_number)) ? '#'.$estimate->id : $estimate->estimate_number }}</h1>
            <div class="date">@lang("modules.estimates.validTill"): {{ $estimate->valid_till->format($global->date_format) }}</div>
        </div>

    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="no">#</th>
            <th class="desc">@lang("modules.invoices.item")</th>
            <th class="qty">@lang("modules.invoices.qty")</th>
            <th class="qty">@lang("modules.invoices.unitPrice") ({!! htmlentities($estimate->currency->currency_code)  !!})</th>
            <th class="unit">@lang("modules.invoices.price") ({!! htmlentities($estimate->currency->currency_code)  !!})</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        @foreach($estimate->items as $item)
            @if($item->type == 'item')
            <tr style="page-break-inside: avoid;">
                <td class="no">{{ ++$count }}</td>
                <td class="desc"><h3>{{ ucfirst($item->item_name) }}</h3>
                    @if(!is_null($item->item_summary))
                        <p class="item-summary">{{ $item->item_summary }}</p>
                    @endif
                </td>
                <td class="qty"><h3>{{ $item->quantity }}</h3></td>
                <td class="qty"><h3>{{ $item->unit_price }}</h3></td>
                <td class="unit">{{ $item->amount }}</td>
            </tr>
            @endif
        @endforeach
        <tr style="page-break-inside: avoid;" class="subtotal">
            <td class="no">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="desc">@lang("modules.invoices.subTotal")</td>
            <td class="unit">{{ $estimate->sub_total }}</td>
        </tr>

        @if($discount != 0 && $discount != '')
        <tr style="page-break-inside: avoid;" class="discount">
            <td class="no">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="desc">@lang("modules.invoices.discount")</td>
            <td class="unit">-{{ $discount }}</td>
        </tr>
        @endif

        @foreach($taxes as $tax)
            <tr style="page-break-inside: avoid;" class="tax">
                <td class="no">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="desc">{{ strtoupper($tax->item_name) }}</td>
                <td class="unit">{{ $tax->amount }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr dontbreak="true">
            <td colspan="4">@lang("modules.invoices.total")</td>
            <td>{{ $estimate->total }}</td>
        </tr>
        </tfoot>
    </table>
    <p>&nbsp;</p>
    <hr>
    <p id="notes">
        @if(!is_null($estimate->note))
            {!! nl2br($estimate->note) !!}<br>
        @endif
        @if(!is_null(invoice_setting()->estimate_terms))
            {!! nl2br(invoice_setting()->estimate_terms) !!}<br>
        @endif
    </p>

</main>
</body>
</html>