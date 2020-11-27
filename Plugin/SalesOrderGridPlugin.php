<?php

namespace Minisport\XtentoGridActionsExt\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection;

class SalesOrderGridPlugin
{
    /**
     * @var boolean
     */
    private $_mapUpdated = false;

    /**
     * Add tracking number field to field map
     *
     * @param  Collection $subject
     * @param  string|array $field
     * @param  null|string|array $condition
     */
    public function beforeAddFieldToFilter(Collection $subject, $field, $condition = null)
    {
        if (!$this->_mapUpdated) {
            $tableName = $subject->getResource()->getTable('sales_shipment_track');
            $subject->addFilterToMap('gridactions_tracking', $tableName . '.track_number');
            $this->_mapUpdated = true;
        }
    }

    /**
     * @param Collection $subject
     * @return null
     * @throws LocalizedException
     */
    public function beforeLoad(Collection $subject)
    {
        if (!$subject->isLoaded()) {
            $primaryKey = $subject->getResource()->getIdFieldName();
            $tableName = $subject->getResource()->getTable('sales_shipment_track');

            $subject->getSelect()->joinLeft(
                $tableName,
                $tableName . '.order_id = main_table.' . $primaryKey
            );

            $subject->getSelect()->distinct();
        }

        return null;
    }
}
