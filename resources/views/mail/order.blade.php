<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order details</title>
    <style>
        @font-face { font-family: 'Roboto'; src: url("{{ asset('tokenized/fonts/Roboto-Light.eot')}}"); src: local("Roboto Light"), local("Roboto-Light"), url("{{ asset('tokenized/fonts/Roboto-Light.eot?#iefix')}}") format("embedded-opentype"), url("{{ asset('tokenized/fonts/Roboto-Light.woff2')}}") format("woff2"), url("{{ asset('tokenized/fonts/Roboto-Light.woff')}}") format("woff"), url("{{ asset('tokenized/fonts/Roboto-Light.ttf')}}") format("truetype"); font-weight: 300; font-style: normal; }
        @font-face { font-family: 'Roboto'; src: url("{{ asset('tokenized/fonts/Roboto-Regular.eot')}}"); src: local("Roboto"), local("Roboto-Regular"), url("{{ asset('tokenized/fonts/Roboto-Regular.eot?#iefix')}}") format("embedded-opentype"), url("{{ asset('tokenized/fonts/Roboto-Regular.woff2')}}") format("woff2"), url("{{ asset('tokenized/fonts/Roboto-Regular.woff')}}") format("woff"), url("{{ asset('tokenized/fonts/Roboto-Regular.ttf')}}") format("truetype"); font-weight: normal; font-style: normal; }
        @font-face { font-family: 'Roboto'; src: url("{{ asset('tokenized/fonts/Roboto-Medium.eot')}}"); src: local("Roboto Medium"), local("Roboto-Medium"), url("{{ asset('tokenized/fonts/Roboto-Medium.eot?#iefix')}}") format("embedded-opentype"), url("{{ asset('tokenized/fonts/Roboto-Medium.woff2')}}") format("woff2"), url("{{ asset('tokenized/fonts/Roboto-Medium.woff')}}") format("woff"), url("{{ asset('tokenized/fonts/Roboto-Medium.ttf')}}") format("truetype"); font-weight: 500; font-style: normal; }
        @font-face { font-family: 'Roboto'; src: url("{{ asset('tokenized/fonts/Roboto-Bold.eot')}}"); src: local("Roboto Bold"), local("Roboto-Bold"), url("{{ asset('tokenized/fonts/Roboto-Bold.eot?#iefix')}}") format("embedded-opentype"), url("{{ asset('tokenized/fonts/Roboto-Bold.woff2')}}") format("woff2"), url("{{ asset('tokenized/fonts/Roboto-Bold.woff')}}") format("woff"), url("{{ asset('tokenized/fonts/Roboto-Bold.ttf')}}") format("truetype"); font-weight: bold; font-style: normal; }
        .email-wraper { background: #e0e8f3; font-size: 14px; line-height: 22px; font-weight: 400; color: #758698; width: 100%;font-family: "Roboto", sans-serif }
        .email-wraper a { color: #1babfe; word-break: break-all; }
        .email-wraper .link-block { display: block; }
        .email-ul { margin: 5px 0; padding: 0; }
        .email-ul:not(:last-child) { margin-bottom: 10px; }
        .email-ul li { list-style: disc; list-style-position: inside; }
        .email-ul-col2 { display: flex; flex-wrap: wrap; }
        .email-ul-col2 li { width: 50%; padding-right: 10px; }
        .email-body { color: #7588a2; width: 96%; max-width: 620px; margin: 0 auto; background: #ffffff; border: 1px solid #e6effb; border-bottom: 4px solid #1babfe; }
        .email-success { border-bottom-color: #00d285; }
        .email-warning { border-bottom-color: #ffc100; }
        .email-btn { background: #1babfe; border-radius: 4px; color: #ffffff !important; display: inline-block; font-size: 13px; font-weight: 600; line-height: 44px; text-align: center; text-decoration: none; text-transform: uppercase; padding: 0 30px; }
        .email-btn-sm { line-height: 38px; }
        .email-header, .email-footer { width: 100%; max-width: 620px; margin: 0 auto; }
        .email-logo { height: 40px; }
        .email-title { font-size: 13px; color: #1babfe; padding-top: 12px; }
        .email-heading { font-size: 18px; color: #1babfe; font-weight: 600; margin: 0; }
        .email-heading-sm { font-size: 16px; }
        .email-heading-s2 { font-size: 15px; color: #000000; font-weight: 600; margin: 0; text-transform: uppercase; margin-bottom: 10px; }
        .email-heading-s3 { font-size: 18px; color: #1babfe; font-weight: 400; margin-bottom: 8px; }
        .email-heading-success { color: #00d285; }
        .email-heading-warning { color: #ffc100; }
        .email-note { margin: 0; font-size: 13px; line-height: 22px; color: #6e81a9; }
        .email-copyright-text { font-size: 13px; }
        .email-social li { display: inline-block; padding: 4px; }
        .email-social li a { display: inline-block; height: 30px; width: 30px; border-radius: 50%; background: #ffffff; }
        .email-social li a img { width: 30px; }
        ul, ol {
            padding: 0px;
            margin: 0px;
        }
        .pdr-2x {
            padding-right: 20px;
        }
        .pdl-2x {
            padding-left: 20px;
        }
        .pdb-4x {
            padding-bottom: 40px;
            padding-top: 40px;
        }
        .pdb-2-5x {
            padding-bottom: 25px;
        }
        .pdb-2x {
            padding-bottom: 20px;
        }
        .pd-3x {
            padding: 30px;
        }
        .mgb-1x {
            margin-bottom: 10px;
        }
        .pdb-1x {
            padding-bottom: 10px;
        }
        p {
            font-size: .9em;
        }
        th{
            color: #7588a2;
        }
        td{
            color: #7588a2;
        }
        tr{
            border-color: #7588a2;
        }

        @media (max-width: 480px) { .email-preview-page .card { border-radius: 0; margin-left: -20px; margin-right: -20px; }
            .email-ul-col2 li { width: 100%; } }
    </style>
</head>
<body>
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <div class="content-area card">
                        <div class="card-innr">
                            <div class="gaps-1x"></div><!-- .gaps -->
                            <table class="email-wraper">
                                <tbody><tr>
                                    <td class="pdt-4x pdb-4x">
                                        <table class="email-header">
                                            <tbody>
                                            <tr>
                                                <td class="text-center pdb-2-5x" style="text-align: center">
                                                    <a href="#"><img class="email-logo" src="{{asset('tokenized/images/logo2x.png')}}" alt="logo"></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="email-body">
                                            <tbody>
                                            <tr>
                                                <td class="pd-3x pdb-2x">
                                                    <h3 class="card-title" style="text-align: center">Booking Preview</h3>
{{--                                                    <p class="mgb-1x">Hi Ishtiyak,</p>--}}
{{--                                                    <p class="mgb-1x">We are pleased to have you as a member of Tokenwiz Family.</p>--}}
{{--                                                    <p class="mgb-1x">Your account is now verified and you can purchase tokens for the ICO. Also you can submit your documents for the KYC from my Account page.</p>--}}
                                                    <p style="margin-top: 30px">Below is your booking details</p>
                                                    <table border="1" cellspacing="0" cellpadding="5">
                                                        <thead>
                                                        <tr>
                                                            <th>Products</th>
                                                            <th>Quantity</th>
                                                            <th>Price</th>
                                                            <th>Total</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach (json_decode($items) as $item)
                                                            <tr>
                                                                <td>{{$item->name}}</td>
                                                                <td>{{$item->quantity}}</td>
                                                                <td>₦{{$item->price}}</td>
                                                                <td>{{$item->total}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    <p>Total: ₦{{$total}}</p>
                                                    <p>Reference: {{$reference}}</p>
                                                    <p>Payment method: {{$payment_method}}</p>
                                                    <p>Date: {{$date}}</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="email-footer">
                                            <tbody>
                                            <tr>
                                                <td class="text-center pdt-2-5x pdl-2x pdr-2x" style="text-align: center">
                                                    <p class="email-copyright-text" style="margin-top: 10px">Copyright © 2021 DevTag.</p>
{{--                                                    <ul class="email-social">--}}
{{--                                                        <li><a href="#"><img src="{{ asset('tokenized/images/brand-b.png')}}" alt="brand"></a></li>--}}
{{--                                                        <li><a href="#"><img src="{{ asset('tokenized/images/brand-e.png')}}" alt="brand"></a></li>--}}
{{--                                                        <li><a href="#"><img src="{{ asset('tokenized/images/brand-d.png')}}" alt="brand"></a></li>--}}
{{--                                                    </ul>--}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
