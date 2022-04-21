<?php

namespace AlwaysOpen\RequestLogger\Observers;

use AlwaysOpen\RequestLogger\Models\RequestLogBaseModel;

class RequestLogObserver
{
    public function saving(RequestLogBaseModel $model)
    {
        if (null === $model->occurred_at) {
            $model->occurred_at = now();
        }
    }
}
