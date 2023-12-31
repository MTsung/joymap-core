<?php

namespace Mtsung\JoymapCore\Http;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function addErrorLog(Throwable $e): string
    {
        $code = $e->getCode();
        if (is_int($code) && $code >= 400 && $code < 500) {
            return $e->getMessage();
        }

        $uuid = Config::get('__tracking_code__');
        $message = LineNotification::getMsgText($e, $uuid);
        event(new SendNotifyEvent($message));
        Log::error($uuid . '---------' . $e->getFile() . ':' . $e->getLine(), [$e]);

        return isProd() ?
            'Error Tracking Code: ' . $uuid :
            $uuid . "\n" . $e->getMessage();
    }

    public function success($return = null, string $msg = '成功'): JsonResponse
    {
        return response()->json(new ApiResource([
            'code' => 1,
            'msg' => $msg,
            'return' => $return,
        ]));
    }

    /**
     * @throws Exception
     */
    public function error(mixed $code, string $msg, $return = null): JsonResponse
    {
        if ($code == 1) {
            throw new Exception('Error Code 不可為 1');
        }

        if (is_string($code)) {
            $code = 0;
        }

        $httpStatus = 500;
        $configKey = 'code.http_status_code.' . $code;
        if (config()->has($configKey)) {
            $httpStatus = config($configKey);
        } else if ($code >= 400 && $code < 500) {
            $httpStatus = $code;
        }

        return response()->json(new ApiResource([
            'code' => $code,
            'msg' => $msg,
            'return' => $return,
        ]), $httpStatus);
    }
}
