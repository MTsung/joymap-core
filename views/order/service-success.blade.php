<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>booking success</title>
</head>
<body style="margin: 0; padding: 0 ;">
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td style="height: 35px; background: #f3f3f3;"></td>
    </tr>
    <tr>
        <td style="background:#f3f3f3 ">
            <table align="center" cellpadding="0" cellspacing="0" width="486"
                   style="border-collapse: separate;  border-spacing: 0 15px;">
                <tr>
                    <td bgcolor="#ffffff" style="padding:15px; border-radius: 5px;">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td valign="top" style="width:60px">
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
                            <tr>
                                <td style="padding-top: 40px ;">
                                    <img src="https://storage.googleapis.com/joymap-store/logo/successful.png"
                                         alt="success" style="display: block; margin: 0 auto;">
                                    <p
                                            style="margin: 10px 0 5px 0;color: #2f2f2f; font-size: 17px; font-weight: 500; text-align:center ;">
                                        感謝您，您已成功預約！
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom:  35px;">
                                    <p style="text-align: center;font-size: 15px;color:#2f2f2f; margin: 0;">
                                        預估施作時間為<span style="font-size: 15px;color:#ef4545;">{{ \Carbon\CarbonInterval::minutes($order->limit_minute ?? 0)->cascade()->forHumans() }}</span>，實際施作時間依店家為準。</p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
                @include('joymap::order.components.service')
                <tr>
                    <td bgcolor="#ffffff" style="padding:15px; border-radius: 5px;">
                        <ul style="list-style: none; padding-left: 0; margin: 0;">
                            <li style="padding: 10px 0;">
                                <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251109205847_DVVidq.png"
                                     alt="phone icon">
                                <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                                    {{ $store->phone }}
                                </p>
                            </li>
                            <li style="padding: 10px 0;">
                                <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251109210424_CYu2Rt.png"
                                     alt="address icon">
                                <a title="店家地址"
                                   style="font-size: 15px; color: #2f2f2f; margin: 0; display: inline-block; text-decoration: none;"
                                   href="https://www.google.com.tw/maps?q={{ $store->full_address }}" target="_blank">
                                    {{ $store->full_address }}
                                </a>
                            </li>
                            @if($store->website)
                                <li style="padding: 10px 0;">
                                    <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251109210427_eymMYZ.png"
                                         alt="link icon">
                                    <a title="店家連結"
                                       style="font-size: 15px; color: #2f2f2f; margin: 0; display: inline-block; text-decoration: none;"
                                       href="{{ $store->website }}" target="_blank">
                                        {{ $store->website }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                        <div style="text-align: center; margin-top: 10px; margin-bottom: 10px;">
                            <a href="{{ $order->info_url }}" title="查看詳細資訊"
                               style="font-size: 17px; width: 245px; height: 50px; line-height: 50px; color: #ffffff; background: #103F93; border:none; border-radius: 5px; text-decoration: none; display: inline-block;">查看詳細資訊</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="height: 35px; background: #f3f3f3;"></td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding: 25px 0 15px 0; text-align: center;">
            <a href="{{ config('joymap.domain.www') }}" style="text-align: center; display: block; margin: 0 auto;">
                <img src="https://storage.googleapis.com/joymap-store/logo/logo.png" alt="Joymap logo"
                     style="display: block; margin: 0 auto" border="0"/>
            </a>
            <p style="font-size: 13px; color: #c4c4c4;">Copyright 2024 Joymap Ltd. All rights reserved.</p>
        </td>
    </tr>
</table>
</body>
</html>
