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
            @if ($order->orderServiceItem->delivery_type == \Mtsung\JoymapCore\Models\OrderServiceItem::DELIVERY_TYPE_DELIVERY && $order->orderDesignatedDriver)
                <li style="padding: 10px 0;">
                    <img width="17" height="17" src="https://storage.googleapis.com/joymap-store/twdd/20251217100621_zMjAUc.png">
                    <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                        到府牽車
                    </p>
                    <p style=" font-size: 15px; color: #103F93; padding: 0px 0; margin: 0; display: inline-block;">
                        {{ $order->orderDesignatedDriver->amount > 0 ? number_format($order->orderDesignatedDriver->amount) . '元' : '免費' }}
                    </p>
                </li>
            @endif
        </ul>
        <hr>
        <center>
            <p style=" font-size: 15px; color: #2f2f2f; padding: 0px 0; margin: 0; display: inline-block;">
                總計：<span style='color:#c4c4c4'><del>{{ $order->orderServiceItem->discount == 1 ? '' : number_format($order->orderServiceItem->original_amount + ($order->orderDesignatedDriver?->amount ?? 0)) }}</del></span>
                {{ number_format($order->orderServiceItem->amount + ($order->orderDesignatedDriver?->amount ?? 0)) }}元
            </p>
        </center>
    </td>
</tr>
