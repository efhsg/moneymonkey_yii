<?php

namespace app\commands;

use ReflectionClass;
use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;

/**
 * Lists controller/action routes and custom URL rules from urlManager->rules.
 */
class RouteController extends Controller
{
    public function actionIndex(): void
    {
        $this->outputSectionHeader("Listing All Controller/Action Routes");
        $this->scanModule(Yii::$app);

        $this->outputSectionHeader("Listing Custom URL Manager Rules");
        $this->listUrlManagerRules();
    }

    protected function scanModule($module, string $prefix = ''): void
    {
        $controllerNamespace = $module->controllerNamespace;
        if ($controllerNamespace) {
            $this->processControllerNamespace($controllerNamespace, $prefix);
        }

        foreach ($module->getModules() as $id => $subModule) {
            $this->scanSubModule($module, $id, $prefix);
        }
    }

    private function processControllerNamespace(string $namespace, string $prefix): void
    {
        $controllerPath = $this->getNamespaceDirectory($namespace);
        if (!$controllerPath || !is_dir($controllerPath)) {
            return;
        }

        $controllerFiles = FileHelper::findFiles($controllerPath, ['only' => ['*Controller.php']]);
        foreach ($controllerFiles as $file) {
            $className = $this->convertPathToClass($file, $namespace);
            $this->listControllerActions($className, $prefix);
        }
    }

    private function scanSubModule($module, string $id, string $prefix): void
    {
        $subModule = $module->getModule($id) ?? $module->getModule($id);
        if ($subModule) {
            $this->scanModule($subModule, $prefix . $id . '/');
        }
    }

    protected function convertPathToClass(string $filePath, string $baseNamespace): string
    {
        $relativePath = str_replace([$this->getNamespaceDirectory($baseNamespace), '.php'], '', $filePath);
        $relativePath = str_replace(DIRECTORY_SEPARATOR, '\\', trim($relativePath, DIRECTORY_SEPARATOR));

        return $baseNamespace . '\\' . $relativePath;
    }

    protected function getNamespaceDirectory(string $namespace): ?string
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $namespace), false);
    }

    protected function listControllerActions(string $className, string $prefix): void
    {
        if (!class_exists($className)) {
            return;
        }

        $reflection = new ReflectionClass($className);
        if ($reflection->isAbstract() || !$reflection->isSubclassOf('yii\web\Controller')) {
            return;
        }

        $controllerId = $this->extractControllerId($reflection->getShortName());

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($this->isValidActionMethod($method->getName())) {
                $actionId = lcfirst(substr($method->getName(), 6));
                $this->stdout($prefix . $controllerId . '/' . $actionId . "\n");
            }
        }
    }

    private function isValidActionMethod(string $methodName): bool
    {
        return $methodName !== 'actions' && strpos($methodName, 'action') === 0 && strlen($methodName) > 6;
    }

    private function extractControllerId(string $shortName): string
    {
        return strtolower(preg_replace('/Controller$/', '', $shortName));
    }

    protected function listUrlManagerRules(): void
    {
        $rules = Yii::$app->urlManager->rules;

        if (empty($rules)) {
            $this->stdout("No custom rules defined.\n");
            return;
        }

        foreach ($rules as $rule) {
            $this->outputUrlRule($rule);
        }
    }

    private function outputUrlRule($rule): void
    {
        if (method_exists($rule, 'getPattern') && method_exists($rule, 'getRoute')) {
            $this->stdout(sprintf("Pattern: %-30s => Route: %s\n", $rule->getPattern(), $rule->getRoute()));
        } else {
            $this->stdout("Unknown rule type: " . get_class($rule) . "\n");
        }
    }

    private function outputSectionHeader(string $header): void
    {
        $this->stdout("=== $header ===\n\n");
    }
}
