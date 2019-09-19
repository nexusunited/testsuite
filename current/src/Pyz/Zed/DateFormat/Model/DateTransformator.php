<?php

namespace Pyz\Zed\DateFormat\Model;

use DateTime;
use DateTimeZone;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class DateTransformator
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $format;

    /**
     * @var array
     */
    private $keys;

    /**
     * @param string $from
     * @param string $to
     * @param string $format
     */
    public function __construct(string $from, string $to, string $format)
    {
        $this->from = $from;
        $this->to = $to;
        $this->format = $format;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $values
     * @param array $keys
     * @param array $options
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function format(TransferInterface $values, array $keys = ['date'], array $options = []): TransferInterface
    {
        $this->parseOptions($options);
        $this->keys = $keys;
        $transferArray = $this->transformData($values->toArray());
        return $values->fromArray($transferArray);
    }

    /**
     * @param array $values
     * @param array $keys
     * @param array $options
     *
     * @return array
     */
    public function formatArray(array $values, array $keys = ['date'], array $options = []): array
    {
        $this->parseOptions($options);
        $this->keys = $keys;
        return $this->transformData($values);
    }

    /**
     * @param array $data $data
     *
     * @return array
     */
    private function transformData(array $data)
    {
        $keys = $this->keys;
        array_walk_recursive($data, function (&$item1, $key) use ($keys) {
                $item1 = $this->formatIfExists($item1, $key, $keys);
        });
        return $data;
    }

    /**
     * @param array $options
     *
     * @return void
     */
    private function parseOptions(array $options)
    {
        foreach ($options as $optionKey => $optionVal) {
            $this->$optionKey = $optionVal;
        }
    }

    /**
     * @param string|\DateTime $value $value
     * @param string $key $key
     * @param array $options $options
     *
     * @return string
     */
    private function formatIfExists($value, string $key, array $options)
    {
        if ($value !== null) {
            foreach ($options as $option) {
                if (strtolower($option) === strtolower($key)) {
                    if ($value instanceof DateTime) {
                        $value = $this->formatDateTime($value);
                    } else {
                        $value = $this->formatValue($value);
                    }
                }
            }
        }
        return $value;
    }

    /**
     * @param \DateTime $dateTimeValue $dateTimeValue
     *
     * @return string
     */
    private function formatDateTime(DateTime $dateTimeValue)
    {
        $dateTimeValue->setTimezone(new DateTimeZone($this->to));
        return $dateTimeValue;
    }

    /**
     * @param string $dateTimeValue $dateTimeValue
     *
     * @return string
     */
    private function formatValue(string $dateTimeValue)
    {
        $date = new DateTime($dateTimeValue, new DateTimeZone($this->from));
        $date->setTimezone(new DateTimeZone($this->to));
        return $date->format($this->format);
    }
}
