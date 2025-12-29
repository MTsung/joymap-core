<?php

namespace Mtsung\JoymapCore\Http;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;

class ApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->resource['code'] ?? 0,
            'msg' => $this->resource['msg'] ?? '',
            'return' => $this->resource['return'] ?? new stdClass(),
            'html' => $this->resource['html'] ?? '', // TODO: APP 更版後移除
        ];
    }
}
