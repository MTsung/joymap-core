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
                                @if ($store->main_food_type_id == 1)
                                <tr>
                                    <td width="33.33%" valign="top">
                                        <p style="margin: 0; color: #2f2f2f; font-size: 15px; margin-bottom: 5px;">
                                            預約日期
                                        </p>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <img src="https://storage.googleapis.com/joymap-store/logo/a-normal-store-date.png" alt="calendar icon"
                                                         style="margin: 5px 4px 0 0;">
                                                </td>
                                                <td>
                                                    <p style="display: inline-block;  color: #fa6c3e;  font-size: 15px ; margin: 0;">
                                                        {{ $reservationDatetime->format('m月 d日') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="33.33%" valign="top">
                                        <p style="margin: 0; color: #2f2f2f; font-size: 15px; margin-bottom: 5px;">
                                            預約時間
                                        </p>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <img src="https://storage.googleapis.com/joymap-store/logo/a-normal-store-time.png" alt="time icon"
                                                         style="margin: 5px 4px 0 0;">
                                                </td>
                                                <td>
                                                    <p style="display: inline-block; color: #fa6c3e; font-size: 15px ; margin: 0;">
                                                        {{ $reservationDatetime->format('H:i') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="33.33%" valign="top">
                                        <p style="margin: 0; color: #2f2f2f; font-size: 15px; margin-bottom: 5px;">
                                            預約人數
                                        </p>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <img src="https://storage.googleapis.com/joymap-store/logo/a-normal-store-people.png" alt="time icon"
                                                         style="margin: 5px 4px 0 0;">
                                                </td>
                                                <td>
                                                    <p style="display: inline-block;  color: #fa6c3e;  font-size: 15px ; margin: 0;">
                                                        {{ $order->adult_num + $order->child_num }}人 {{ $order->child_num > 0 ? "(含{$order->child_num}位兒童)" : '' }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                @else

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
                                                        {!! $order->service_item_text !!}
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
                                                    總計：<span style='color:#c4c4c4'><del>{{ $order->orderServiceItem->discount == 1 ? '' : number_format($order->orderServiceItem->original_amount) }}</del></span> {{ number_format($order->orderServiceItem->amount) }}元
                                                </p>
                                            </center>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                            @if ($store->main_food_type_id == 1)
                            <div style="width: 100%; height: 1px; background: #e7e7e7; margin: 14px 0;"></div>
                            <p style="font-size: 15px; color: #2f2f2f; margin: 0;">{{ $order->comment }}</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding:15px; border-radius: 5px;">
                            <p style="margin: 0; color: #2f2f2f ; font-weight: 500; font-size: 15px;">{{ $order->name }} 您好！</p>
                            <p style="color: #2f2f2f; font-size: 15px;">還記得您
                                <span style="color: #fa6c3e;"> {{ $reservationDatetime->format('m月 d日') }} </span>的預約嗎？請協助我們確認，您是否保留這項預約呢？
                            </p>
                            <table align="center" cellpadding="0" cellspacing="0"
                                   style="border-collapse: separate;  border-spacing: 0 20px;">
                                <tr>
                                    <td style="width: 100%; display: block; text-align: center;">
                                        <a href="{{ $order->info_url }}" title="保留預約"
                                           style="font-size: 17px ;width: 245px; height: 50px; line-height: 50px ; color: #ffffff; background: #fa6c3e; border:none ; border-radius: 5px; text-decoration: none; display: inline-block;">保留預約</a>
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
                    @if ($store->main_food_type_id == 1)
                    <tr>
                        <td style="padding: 0;">
                            <a href="https://onelink.to/joymap0509" target="_blank" style="display: block; text-decoration: none;">
                                <div style=" border-radius: 5px;overflow: hidden;height: 246px;background-size: cover;
                                  background-position: center;
                                  background-image: url('https://storage.googleapis.com/joymap-store/carousel/20240630132606_gJkjKy.png');
                                  ">
                                </div>
                            </a>
                        </td>
                    </tr>
                    @endif
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
