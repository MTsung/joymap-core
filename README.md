# Joymap

## Installation

```bash
composer require mtsung/joymap-core
```

## Config Prefix

```php
config('joymap.xxxxxxxx');
```

## Lang Prefix

```php
__('joymap::xxxxxxxx');
```

## GCS Config

`config/filesystems.php`

```php
use League\Flysystem\GoogleCloudStorage\PortableVisibilityHandler;

return [
    'disks' => [
        'gcs' => [
            'driver' => 'gcs',
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', ''),
            'key_file' => json_decode(@file_get_contents(base_path(env('GOOGLE_CLOUD_KEY_FILE', null))), true),
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', ''),
            'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', ''),
            'storage_api_uri' => env('GOOGLE_CLOUD_STORAGE_API_URI', null),
            'visibility' => PortableVisibilityHandler::NO_PREDEFINED_VISIBILITY,
            'throw' => true,
        ],
    ],
];
```

## Image Compress Config

`config/image.php`

```php
return [
    'driver' => 'imagick'
];
```

## 藍新建立/修改商店 Config

`config/logging.php`

```php
return [
    'channels' => [
        'spgateway-store' => [
            'driver' => 'daily',
            'path' => storage_path('logs/spgateway/store.log'),
            'days' => 90,
        ],
    ],
];
```

## Exception Error Code To Http Status Code Config

`config/code.php`

```php
    
return [
    'http_status_code' =>[
        41000 => 422,
        41001 => 422,
        51000 => 500,
    ],
];
```

## Exception Error Msg Send To LINE Notify

`app/Exceptions/Handler.php`

```php
    public function render($request, Throwable $e): Response
    {
        if ($this->shouldReport($e)) {
            $message = LineNotification::getMsgText($e);
            event(new SendErrorNotifyEvent($message));
        }

        return parent::render($request, $e);
    }
```

## env Example

```env
# Hitrust 參數
HITRUST_URL='https://testtrustlink.hitrust.com.tw/TrustLink/TrxReqForJava'
# 白名單網域
HITRUST_REFERER_URL='https://member-test.twdd.tw'
HITRUST_RETURN_URL='https://webapi-test.joymap.tw/credit-card/return'
HITRUST_CALLBACK_URL='https://webapi-test.joymap.tw/credit-card/callback'

# 智付通/藍新
# 退刷
SPGATEWAY_CLOSE_URL='https://ccore.newebpay.com/API/CreditCard/Close'
# 取消授權
SPGATEWAY_CANCEL_URL='https://ccore.newebpay.com/API/CreditCard/Cancel'
# 授權
SPGATEWAY_CREDIT_CARD_URL='https://ccore.newebpay.com/API/CreditCard'
# 查詢訂單
SPGATEWAY_QUERY_URL='https://ccore.newebpay.com/API/QueryTradeInfo'
# 金流合作推廣商 平台費用扣款
SPGATEWAY_CHARGE_INSTRUCT_URL='https://ccore.newebpay.com/API/ChargeInstruct'
# PartnerID_
SPGATEWAY_STORE_PARTNER_ID='TWDD'
SPGATEWAY_MERCHANT_HASH_KEY=''
SPGATEWAY_MERCHANT_IV_KEY=''
SPGATEWAY_MERCHANT_PREFIX=''

# 建立商店相關參數
SPGATEWAY_STORE_AGREED_FEE=
SPGATEWAY_STORE_AGREED_DAY=
SPGATEWAY_CREATE_STORE_URL='https://ccore.newebpay.com/API/AddMerchant'
SPGATEWAY_CREATE_STORE_VERSION=1.2
# 修改商店相關參數
SPGATEWAY_UPDATE_STORE_URL='https://ccore.newebpay.com/API/AddMerchant/modify'
SPGATEWAY_UPDATE_STORE_VERSION=1.0

# FCM 推播設定
FCM_URL='https://fcm.googleapis.com/fcm/send'
FCM_KEY=

# GORUSH 推播設定
GORUSH_HOST='http://localhost'
GORUSH_PORT=9999
GORUSH_PATH='/api/push'
GORUSH_TOPIC_MEMBER=
GORUSH_TOPIC_STORE='com.ChyunYueh.JoyShop'

# Joymap WEBAPI Url
WEBAPI_URL='https://webapi-test.joymap.tw'
# Joymap WWW Url
WWW_URL='https://www-test.joymap.tw'
# Joymap Booking Url
ORDER_DOMAIN='https://web-test.joymap.tw/'

# GCS
GOOGLE_CLOUD_PROJECT_ID=api-project-288918646937
GOOGLE_CLOUD_KEY_FILE=storage/gcs_key.json
GOOGLE_CLOUD_STORAGE_BUCKET=joymap-store
GOOGLE_CLOUD_STORAGE_PATH_PREFIX=
GOOGLE_CLOUD_STORAGE_API_URI='https://storage.googleapis.com/'

# Error 時是否發送到 LINE NOTIFY
SEND_ERROR_LOG_LINE=false
LINE_NOTIFY_TOKEN=
```
