<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reminder</title>
</head>
<body style="margin: 0; padding: 0 ;">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="height: 35px; background: #f3f3f3;"></td>
        </tr>
        <tr>
            <td style="background:#f3f3f3;">
                <table align="center" cellpadding="0" cellspacing="0" width="486"
                       style="border-collapse: separate;  border-spacing: 0 15px;">
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 15px; border-radius: 5px;">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 15px;">
                                            <tr>
                                                <td valign="top" style="width:60px;">
                                                    <img src="{{ $store->logo_url }}" alt="store logo"
                                                         style="width: 50px; height: 50px; border-radius: 5px; background-position: center;">
                                                </td>
                                                <td valign="top">
                                                    <p style="margin: 0; font-size: 20px; color: #2f2f2f; margin-bottom: 1px; font-weight: 500;">
                                                        {{ $store->name }}
                                                    </p>
                                                    <p style="margin: 0; font-size: 15px; color: #2f2f2f;">{{ $store->food_type_full_name }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td bgcolor="#ffffff" style="padding:15px; border-radius: 5px;">
                                        <ul style="list-style: none; padding-left: 0; margin: 0;">
                                            <li style="padding: 10px 0;">
                                                <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251109160030_wnFy98.png">
                                                <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                                                    預約時間
                                                </p>
                                                <p style=" font-size: 15px; color: #103F93; padding: 0px 0; margin: 0; display: inline-block;">
                                                    {{ $reservationDatetime->format('m月 d日') }} {{ $reservationDatetime->format('H:i') }}
                                                </p>
                                            </li>
                                            <li style="padding: 10px 0;">
                                                <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251109160044_xacExA.png">
                                                <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                                                    服務類型
                                                </p>
                                                <p style=" font-size: 15px; color: #103F93; padding: 0px 0; margin: 0; display: inline-block;">
                                                    {{ $order->orderServiceItem->serviceType?->name ?? '' }} ({{ $order->orderServiceItem->serviceItem?->serviceCategory?->name }})
                                                </p>
                                            </li>
                                            <li style="padding: 10px 0;">
                                                <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251109155651_41IYqP.png">
                                                <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                                                    服務項目
                                                </p>
                                                <p style=" font-size: 15px; color: #103F93; padding: 0px 0; margin: 0; display: inline-block;">
                                                    {{ $order->service_item_text }}
                                                </p>
                                            </li>
                                            <li style="padding: 10px 0;">
                                                <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251109160108_d2OLLi.png">
                                                <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                                                    加購項目
                                                </p>
                                                <p style=" font-size: 15px; color: #103F93; padding: 0px 0; margin: 0; display: inline-block;">
                                                    {{ $order->addon_item_text }}
                                                </p>
                                            </li>
                                        </ul>
                                        <hr>
                                        <center>
                                            <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                                                總計：{{ number_format($order->orderServiceItem->amount) }}元
                                            </p>
                                        </center>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding:15px; border-radius: 5px;">
                            <p style="margin: 0; color: #2f2f2f ; font-weight: 500; font-size: 15px;">{{ $order->name }} 您好！</p>
                            <p style="color: #2f2f2f; font-size: 15px;">還記得您
                                <span style="color: #103F93;"> {{ $reservationDatetime->format('m月 d日') }} </span>的預約嗎？請協助我們確認，您是否保留這項預約呢？
                            </p>
                            <table align="center" cellpadding="0" cellspacing="0"
                                   style="border-collapse: separate;  border-spacing: 0 20px;">
                                <tr>
                                    <td style="width: 100%; display: block; text-align: center;">
                                        <a href="{{ $order->info_url }}" title="保留預約"
                                           style="font-size: 17px ;width: 245px; height: 50px; line-height: 50px ; color: #ffffff; background: #103F93; border:none ; border-radius: 5px; text-decoration: none; display: inline-block;">保留預約</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $order->info_url }}" title="取消預約"
                                           style="font-size: 15px; color: #c4c4c4; text-decoration: none;">取消預約</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="height: 35px; background: #f3f3f3; "></td>
        </tr>
        <tr>
            <td bgcolor="#ffffff" style="padding:25px 0 15px 0; text-align: center;">
                <a href="{{ config('joymap.domain.www') }}" style="text-align: center; display: block; margin: 0 auto;">
                    <img src="https://storage.googleapis.com/joymap-store/logo/logo.png" alt="Joymap logo" style="display: block; margin: 0 auto;" border="0" />
                </a>
                <p style="font-size: 13px; color: #c4c4c4;">Copyright 2024 Joymap Ltd. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
