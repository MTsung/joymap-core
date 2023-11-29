<?php

namespace Mtsung\JoymapCore\Http;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mtsung\JoymapCore\Events\ErrorNotify\SendErrorNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function addErrorLog(Throwable $e): string
    {
        $code = $e->getCode();
        if ($code >= 400 && $code < 500) {
            return $e->getMessage();
        }

        $uuid = Str::uuid();
        $message = LineNotification::getMsgText($e, $uuid);
        event(new SendErrorNotifyEvent($message));
        Log::error($uuid . '---------' . $e->getFile() . ':' . $e->getLine(), [$e]);

        return isProd() ?
            $uuid :
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
    public function error(int $code, string $msg, $return = null): JsonResponse
    {
        if ($code == 1) {
            throw new Exception('Error Code 不可為 1');
        }

        $httpStatus = 500;
        $configKey = 'code.http_status_code.' . $code;
        if (config()->has($configKey)) {
            $httpStatus = config($configKey);
        }

        return response()->json(new ApiResource([
            'code' => $code,
            'msg' => $msg,
            'return' => $return,
        ]), $httpStatus);
    }
}
