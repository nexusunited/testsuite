<?php

namespace Pyz\Zed\ApiDocumentor\Business\Model;

use ReflectionClass;
use ReflectionMethod;
use ReflectionType;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class Reflection
{
    /**
     * @param string $abstractController $abstractController
     *
     * @return array
     */
    public function getControllerAnnotations(string $abstractController): array
    {
        $reflection = new ReflectionClass($abstractController);
        return $this->getClassAnnotations($reflection);
    }

    /**
     * @param \ReflectionClass $reflection $reflection
     *
     * @return array
     */
    protected function getClassAnnotations(ReflectionClass $reflection): array
    {
        $result = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();

            if ($this->isAction($methodName)) {
                $transferFilename = $this->getTransferObject($method);

                if ($transferFilename !== '') {
                    $result[$methodName] = [
                        'methodName' => str_replace('Action', '', $methodName),
                        'transferName' => $transferFilename,
                        'transferParams' => $this->getTransferAnnotations($transferFilename),
                        'docString' => $method->getDocComment(),
                        'parameters' => $this->annotateIncomingParameters($method),
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * @param \ReflectionType|null $type
     * @param \ReflectionClass|null $class
     *
     * @return array
     */
    protected function annotateType(?ReflectionType $type = null, ?ReflectionClass $class = null): array
    {
        return [
            'type' => $class !== null ? $class->getName() : $type,
            'isTransfer' => $class !== null ? $class->isSubclassOf(AbstractTransfer::class) : false,
        ];
    }

    /**
     * @param string $methodName $methodName
     *
     * @return bool
     */
    private function isAction(string $methodName): bool
    {
        $valid = false;
        if (stripos($methodName, 'action') !== false) {
            $valid = true;
        }
        return $valid;
    }

    /**
     * @param \ReflectionMethod $method $method
     *
     * @return string
     */
    private function getTransferObject(ReflectionMethod $method): string
    {
        $fileName = '';
        $docComment = $method->getDocComment();

        preg_match_all('#@(.*?)\n#s', $docComment, $methodAnnotations);

        foreach ($methodAnnotations[1] as $annotation) {
            $annotationExploded = explode(' ', $annotation);
            if (isset($annotationExploded[0]) &&
                $annotationExploded[0] === 'api') {
                $fileName = $annotationExploded[1];
                break;
            }
        }
        return $fileName;
    }

    /**
     * @param \ReflectionMethod $method $method
     *
     * @return array
     */
    protected function annotateIncomingParameters(ReflectionMethod $method): array
    {
        $result = [];
        foreach ($method->getParameters() as $parameter) {
            $result[$parameter->getName()] = $this->annotateType($parameter->getType(), $parameter->getClass());
        }

        return $result;
    }

    /**
     * @param string $transfer $transfer
     *
     * @return array
     */
    public function getTransferAnnotations(string $transfer): array
    {
        $reflection = new ReflectionClass($transfer);
        $metadata = $reflection->getDefaultProperties()['transferMetadata'];
        $result = [];
        foreach ($metadata as $attribute => $properties) {
            unset($properties['is_collection'], $properties["is_transfer"]);
            if (strpos($properties['type'], 'Generated') !== false) {
                $properties['is_link'] = true;
            } else {
                $properties['is_link'] = false;
            }

            $result[$attribute] = $properties;
        }
        return $result;
    }
}
