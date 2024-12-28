<?php

namespace app\helpers;

class BreadcrumbHelper
{
    /**
     * Generate breadcrumbs for a model-based view.
     *
     * @param array $breadcrumbParts An array of ["label" => "URL|null"] representing breadcrumb parts.
     * @param object|null $model The model instance (optional).
     * @param string $actionLabel The label for the current action (e.g., "View").
     * @return array The generated breadcrumbs array.
     */
    public static function generateModelBreadcrumbs(array $breadcrumbParts, ?object $model, string $actionLabel): array
    {
        $breadcrumbs = [];

        // Loop through breadcrumb parts and add them to the breadcrumbs array
        foreach ($breadcrumbParts as $part) {
            $breadcrumbs[] = [
                'label' => $part['label'],
                'url' => $part['url'], // URL or null for static breadcrumb
            ];
        }

        // Add the model-specific breadcrumb if a model is provided
        if ($model !== null) {
            $breadcrumbs[] = [
                'label' => $model->name,
                'url' => ['view', 'id' => $model->id],
            ];
        }

        // Add the action label as the last breadcrumb (static, no URL)
        $breadcrumbs[] = [
            'label' => $actionLabel,
            'url' => null,
        ];

        return $breadcrumbs;
    }
}
