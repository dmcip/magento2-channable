<?php
/**
 * Copyright © 2017 Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magmodules\Channable\Cron;

use Magmodules\Channable\Model\Item as ItemModel;
use Magmodules\Channable\Helper\Item as ItemHelper;
use Psr\Log\LoggerInterface as Logger;

class ItemUpdate
{

    private $itemHelper;
    private $itemModel;
    private $logger;

    /**
     * ItemUpdate constructor.
     *
     * @param ItemModel  $itemModel
     * @param ItemHelper $itemHelper
     * @param Logger     $logger
     */
    public function __construct(
        ItemModel $itemModel,
        ItemHelper $itemHelper,
        Logger $logger
    ) {
        $this->itemHelper = $itemHelper;
        $this->itemModel = $itemModel;
        $this->logger = $logger;
    }


    public function execute()
    {
        $results = [];
        if(!$this->itemHelper->isCronEnabled()) {
            return $results;
        }

        $storeIds = $this->itemHelper->getStoreIds();
        foreach ($storeIds as $storeId) {
            try {
                $results[] = $this->itemModel->updateByStore($storeId);
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        return $results;
    }
}
