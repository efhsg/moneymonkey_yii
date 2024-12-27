<?php

namespace app\helpers;

class BreadcrumbHelper
{
    public static function generateModelBreadcrumbs(string $label, string $indexUrl, $model, string $actionLabel): array
    {
        $breadcrumbs = [
            ['label' => $label, 'url' => [$indexUrl]],
        ];

        if ($model !== null) {
            $breadcrumbs[] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
        }

        $breadcrumbs[] = $actionLabel;

        return $breadcrumbs;
    }
}
