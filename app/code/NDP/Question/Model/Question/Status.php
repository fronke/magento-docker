<?php

namespace NDP\Question\Model\Question;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Status values
     */

    const STATUS_PUBLIC = 0;

    const STATUS_PRIVATE = 1;

    const STATUS_PENDING = 2;

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [self::STATUS_PENDING => __('Pending'),
            self::STATUS_PUBLIC => __('Public'),
            self::STATUS_PRIVATE => __('Private')];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }

    public function toOptionArray()
    {
        return $this->getOptionArray();
    }
}
