<?php

namespace Pyz\Zed\LiselMonitoring\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use NxsSpryker\Zed\Monitoring\Communication\Plugin\MonitoringAlertPluginInterface;
use Pyz\Zed\LiselMonitoring\Communication\Plugin\Mail\LiselMonitoringAlertMailTypePlugin;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use function in_array;

/**
 * @method \Shared\Zed\LiselMonitoring\Communication\LiselMonitoringCommunicationFactory getFactory()
 * @method \Shared\Zed\LiselMonitoring\LiselMonitoringConfig getConfig()
 */
class LiselDeliveryTrackingAlertPlugin extends AbstractPlugin implements MonitoringAlertPluginInterface
{
    public const EXCEPTION_CRITERIA = [LiselDeliveryTrackingCriteriaPlugin::CRITERIA_NAME];

    /**
     * @param string $criteriaName
     *
     * @return bool
     */
    public function isResponsibleForCriteria(string $criteriaName): bool
    {
        return in_array($criteriaName, self::EXCEPTION_CRITERIA);
    }

    /**
     * @return void
     */
    public function triggerAlert(): void
    {
        $alertRecipients = $this->getConfig()->getMonitoringExceptionEmailRecipients();
        foreach ($alertRecipients as $recipient) {
            $this->sendEmailToRecipient($recipient);
        }
    }

    /**
     * @param string $recipient
     *
     * @return void
     */
    private function sendEmailToRecipient(string $recipient): void
    {
        $mailTransfer = new MailTransfer();
        $mailRecipient = (new MailRecipientTransfer())
            ->setEmail($recipient)
            ->setName('');
        $mailTransfer->addRecipient($mailRecipient);
        $mailTransfer->setType(LiselMonitoringAlertMailTypePlugin::MAIL_TYPE);

        $this->getFactory()->getMailFacade()->handleMail($mailTransfer);
    }
}
