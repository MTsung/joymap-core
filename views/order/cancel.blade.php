<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cancel success</title>
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
                                    <td style="padding-top: 40px;">
                                        <img src="https://storage.googleapis.com/joymap-store/logo/successful.png" alt="success" style=" display: block; margin: 0 auto;">
                                        <p style=" margin: 10px 0 5px 0; color: #2f2f2f; font-size: 17px; font-weight: 500; text-align:center;">已為您取消訂位</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:  35px;">
                                        <p style="text-align: center; font-size: 15px; color:#2f2f2f; margin: 0;">
                                            期待下次再為您服務！</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding:15px; border-radius: 5px;">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td width="33.33%" valign="top">
                                        <p style="margin: 0; color: #2f2f2f; font-size: 15px; margin-bottom: 5px;">
                                            訂位日期
                                        </p>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <img src="https://storage.googleapis.com/joymap-store/logo/a-normal-store-date.png" alt="calendar icon"
                                                         style="margin: 5px 4px 0 0;">
                                                </td>

                                                <td>
                                                    <p style="display: inline-block;  color: #fa6c3e;  font-size: 15px ; margin: 0;">
                                                        {{ $reservationDateTime->format('m月 d日') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="33.33%" valign="top">
                                        <p style="margin: 0; color: #2f2f2f; font-size: 15px; margin-bottom: 5px;">
                                            訂位時間
                                        </p>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <img src="https://storage.googleapis.com/joymap-store/logo/a-normal-store-time.png" alt="time icon"
                                                         style="margin: 5px 4px 0 0;">
                                                </td>
                                                <td>
                                                    <p style="display: inline-block;  color: #fa6c3e;  font-size: 15px ; margin: 0;">
                                                        {{ $reservationDateTime->format('H:i') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="33.33%" valign="top">
                                        <p style="margin: 0; color: #2f2f2f; font-size: 15px; margin-bottom: 5px;">
                                            訂位人數
                                        </p>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <img src="https://storage.googleapis.com/joymap-store/logo/a-normal-store-people.png" alt="time icon"
                                                         style="margin: 5px 4px 0 0;">
                                                </td>
                                                <td>
                                                    <p style="display: inline-block;  color: #fa6c3e;  font-size: 15px; margin: 0;">
                                                        {{ $order->adult_num + $order->child_num }}人 {{ $order->child_num > 0 ? "(含{$order->child_num}位兒童)" : '' }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <div style="width: 100%; height: 1px; background: #e7e7e7; margin: 14px 0;"></div>
                            <p style="font-size: 15px; color: #2f2f2f; margin: 0;">{{ $order->comment }}</p>
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
                <a href="{{ config('joymap.domain.www') }}" style="text-align: center;display: block; margin: 0 auto;">
                    <img src="https://storage.googleapis.com/joymap-store/logo/logo.png" alt="Joymap logo" style="display: block; margin: 0 auto;" border="0" />
                </a>
                <p style="font-size: 13px; color: #c4c4c4;">Copyright 2021 Joymap Ltd. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
