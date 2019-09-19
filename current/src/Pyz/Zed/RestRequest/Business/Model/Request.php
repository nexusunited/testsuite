<?php

namespace Pyz\Zed\RestRequest\Business\Model;

use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use Pyz\Zed\RestRequest\Business\Exception\JsonException;
use Pyz\Zed\RestRequest\Business\Exception\ValidationException;
use Pyz\Zed\RestRequest\Plugin\ValidationRuleCollection;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use function json_decode;

class Request
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    private $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $util
     */
    public function __construct(UtilEncodingServiceInterface $util)
    {
        $this->utilEncodingService = $util;
    }

    /**
     * @param string $request
     *
     * @return array
     */
    public function getData(string $request): array
    {
        return $this->jsonToArray($request);
    }

    /**
     * @param string $request
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleCollection $rules
     *
     * @return array
     */
    public function validateData(string $request, ValidationRuleCollection $rules): array
    {
        $this->checkRules($request, $rules);
        return $this->jsonToArray($request);
    }

    /**
     * @param string $request $request
     *
     * @throws \Shared\Zed\RestRequest\Business\Exception\JsonException
     *
     * @return array
     */
    private function jsonToArray(string $request): array
    {
        $data = $this->utilEncodingService->decodeJson($request, true);

        if ($data === null
            && json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException();
        }
        return $data;
    }

    /**
     * @param string $request
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleCollection $rules
     *
     * @throws \Shared\Zed\RestRequest\Business\Exception\ValidationException
     *
     * @return void
     */
    private function checkRules(string $request, ValidationRuleCollection $rules): void
    {
        foreach ($rules as $validationRules) {
            $schemaStorage = new SchemaStorage();

            $jsonSchema = file_get_contents($validationRules->filePath());
            $jsonSchemaObject = json_decode($jsonSchema);
            $schemaStorage->addSchema('file://' . $validationRules->ruleName(), $jsonSchemaObject);

            $jsonValidator = new Validator(new Factory($schemaStorage));

            $jsonToValidateObject = json_decode($request);
            $jsonValidator->validate($jsonToValidateObject, $jsonSchemaObject);
            if (!$jsonValidator->isValid()) {
                $exceptionMsg = '';
                foreach ($jsonValidator->getErrors() as $error) {
                    $exceptionMsg .= $error['property'] . '=>' . $error['message'];
                }
                throw new ValidationException($exceptionMsg . " -- Your Request ($request)");
            }
        }
    }
}
