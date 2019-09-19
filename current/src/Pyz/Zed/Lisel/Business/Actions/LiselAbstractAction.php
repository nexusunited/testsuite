<?php

namespace Pyz\Zed\Lisel\Business\Actions;

use Pyz\Shared\LiselRequest\LiselRequestConstants;
use Pyz\Zed\LiselRequest\Business\LiselRequestFacadeInterface;
use Pyz\Zed\RestRequest\Business\RestRequestFacadeInterface;
use Pyz\Zed\RestRequest\Plugin\ValidationRuleCollection;
use Pyz\Zed\RestRequest\Plugin\ValidationRuleInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class LiselAbstractAction implements LiselActionInterface
{
    /**
     * @var \Shared\Zed\LiselRequest\Business\LiselRequestFacadeInterface
     */
    protected $liselRequestFacade;

    /**
     * @var \Shared\Zed\RestRequest\Business\RestRequestFacadeInterface
     */
    protected $restRequestFacade;

    /**
     * @var \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface
     */
    protected $validationRule;

    /**
     * @param \Shared\Zed\LiselRequest\Business\LiselRequestFacadeInterface $liselRequestFacade
     * @param \Shared\Zed\RestRequest\Business\RestRequestFacadeInterface $restRequestFacade
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface $validationRules
     */
    public function __construct(
        LiselRequestFacadeInterface $liselRequestFacade,
        RestRequestFacadeInterface $restRequestFacade,
        ValidationRuleInterface $validationRules
    ) {
        $this->liselRequestFacade = $liselRequestFacade;
        $this->restRequestFacade = $restRequestFacade;
        $this->validationRule = $validationRules;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return LiselRequestConstants::LISEL_STATUS_TYPE;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function execute(Request $request): void
    {
        $this->restRequestFacade->validateData($request->getContent(), new ValidationRuleCollection($this->validationRule));
        $this->liselRequestFacade->storePlainRequest(
            $this->getName(),
            $this->restRequestFacade->getData($request->getContent())
        );
        $this->liselRequestFacade->setLastLiselRequestTimeStamp();
    }
}
