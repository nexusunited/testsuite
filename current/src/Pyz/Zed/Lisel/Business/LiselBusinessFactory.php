<?php

namespace Pyz\Zed\Lisel\Business;

use Pyz\Zed\ApiDocumentor\Business\ApiDocumentorFacadeInterface;
use Pyz\Zed\Lisel\Business\Actions\LiselStatusAction;
use Pyz\Zed\Lisel\Business\Actions\LiselTimeAction;
use Pyz\Zed\Lisel\Business\Actions\LiselTourAction;
use Pyz\Zed\Lisel\Business\ValidationRules\StatusActionRuleV1;
use Pyz\Zed\Lisel\Business\ValidationRules\TimeActionRuleV1;
use Pyz\Zed\Lisel\Business\ValidationRules\TourActionRuleV1;
use Pyz\Zed\Lisel\Business\ValidationRules\TourActionRuleV2;
use Pyz\Zed\Lisel\LiselDependencyProvider;
use Pyz\Zed\LiselAnalyser\Business\LiselAnalyserFacadeInterface;
use Pyz\Zed\LiselRequest\Business\LiselRequestFacadeInterface;
use Pyz\Zed\NxsLogger\Business\NxsLoggerFacadeInterface;
use Pyz\Zed\RestAuth\Business\RestAuthFacadeInterface;
use Pyz\Zed\RestRequest\Business\RestRequestFacadeInterface;
use Pyz\Zed\RestRequest\Plugin\ValidationRuleInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class LiselBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\Lisel\Business\LiselActionHandler
     */
    public function createLiselControllerHandler(): LiselActionHandler
    {
        return new LiselActionHandler($this->getNxsLoggerFacade(), $this->getAuthFacade());
    }

    /**
     * @return \Shared\Zed\ApiDocumentor\Business\ApiDocumentorFacadeInterface
     */
    public function getDocumentorFacade(): ApiDocumentorFacadeInterface
    {
        return $this->getProvidedDependency(LiselDependencyProvider::FACADE_API_DOCUMENTOR);
    }

    /**
     * @return \Shared\Zed\LiselRequest\Business\LiselRequestFacadeInterface
     */
    public function getLiselRequestFacade(): LiselRequestFacadeInterface
    {
        return $this->getProvidedDependency(LiselDependencyProvider::FACADE_LISEL_REQUEST);
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\LiselAnalyserFacadeInterface
     */
    public function getLiselAnalyserFacade(): LiselAnalyserFacadeInterface
    {
        return $this->getProvidedDependency(LiselDependencyProvider::LISEL_ANALYSER_FACADE);
    }

    /**
     * @return \Shared\Zed\NxsLogger\Business\NxsLoggerFacadeInterface
     */
    public function getNxsLoggerFacade(): NxsLoggerFacadeInterface
    {
        return $this->getProvidedDependency(LiselDependencyProvider::FACADE_NXS_LOGGER);
    }

    /**
     * @return \Shared\Zed\RestRequest\Business\RestRequestFacadeInterface
     */
    public function getRestRequestFacade(): RestRequestFacadeInterface
    {
        return $this->getProvidedDependency(LiselDependencyProvider::REST_REQUEST_FACADE);
    }

    /**
     * @return \Shared\Zed\RestAuth\Business\RestAuthFacadeInterface
     */
    public function getAuthFacade(): RestAuthFacadeInterface
    {
        return $this->getProvidedDependency(LiselDependencyProvider::FACADE_AUTH);
    }

    /**
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface $rules
     *
     * @return \Shared\Zed\Lisel\Business\Actions\LiselTourAction
     */
    public function createTourAction(ValidationRuleInterface $rules): LiselTourAction
    {
        return new LiselTourAction(
            $this->getLiselRequestFacade(),
            $this->getRestRequestFacade(),
            $rules
        );
    }

    /**
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface $rules
     *
     * @return \Shared\Zed\Lisel\Business\Actions\LiselTimeAction
     */
    public function createTimeAction(ValidationRuleInterface $rules): LiselTimeAction
    {
        return new LiselTimeAction(
            $this->getLiselRequestFacade(),
            $this->getRestRequestFacade(),
            $rules
        );
    }

    /**
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface $rules
     *
     * @return \Shared\Zed\Lisel\Business\Actions\LiselStatusAction
     */
    public function createStatusAction(ValidationRuleInterface $rules): LiselStatusAction
    {
        return new LiselStatusAction(
            $this->getLiselRequestFacade(),
            $this->getRestRequestFacade(),
            $rules
        );
    }

    /**
     * @return \Shared\Zed\Lisel\Business\ValidationRules\StatusActionRuleV1
     */
    public function createStatusActionRulesV1(): StatusActionRuleV1
    {
        return new StatusActionRuleV1();
    }

    /**
     * @return \Shared\Zed\Lisel\Business\ValidationRules\TimeActionRuleV1
     */
    public function createTimeActionRulesV1(): TimeActionRuleV1
    {
        return new TimeActionRuleV1();
    }

    /**
     * @return \Shared\Zed\Lisel\Business\ValidationRules\TourActionRuleV1
     */
    public function createTourActionRulesV1(): TourActionRuleV1
    {
        return new TourActionRuleV1();
    }

    /**
     * @return \Shared\Zed\Lisel\Business\ValidationRules\TourActionRuleV2
     */
    public function createTourActionRulesV2(): TourActionRuleV2
    {
        return new TourActionRuleV2();
    }
}
