<?php

namespace App\Http\Resources\Api;

use App\Models\Enum\UserEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch($this->status) {
            case -1:
                $this->status = '已删除';
                break;
            case 0:
                $this->status = '正常';
                break;
            case 1:
                $this->status = '冻结';
                break;
        }
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'status'        => UserEnum::getStatusName($this->status),
            'created_at'    => (string)$this->created_at,
            'updated_at'    => (string)$this->updated_at
        ];
    }
}

//返回单一用户(单一的资源)
//return $this->success(new UserResource($user));
//返回用户列表(资源列表)
//return UserResource::collection($users);
//这里不能用$this->success(UserResource::collection($users))
//否则不能返回分页标签信息
