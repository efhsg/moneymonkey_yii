<?php

namespace app\helpers;

class BreadcrumbHelper {
    public static function generateModelBreadcrumbs(string $label, string $indexUrl, $model, string $actionLabel): array
    {
        return [
            ['label' => $label, 'url' => [$indexUrl]],
            ['label' => $model->name, 'url' => ['view', 'id' => $model->id]],
            $actionLabel,
        ];
    }
}
