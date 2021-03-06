<?php
/**
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Catalog\Controller\Adminhtml\Category;

class Tree extends \Magento\Catalog\Controller\Adminhtml\Category
{
    /**
     * Tree Action
     * Retrieve category tree
     *
     * @return void
     */
    public function execute()
    {
        $storeId = (int)$this->getRequest()->getParam('store');
        $categoryId = (int)$this->getRequest()->getParam('id');

        if ($storeId) {
            if (!$categoryId) {
                $store = $this->_objectManager->get('Magento\Framework\StoreManagerInterface')->getStore($storeId);
                $rootId = $store->getRootCategoryId();
                $this->getRequest()->setParam('id', $rootId);
            }
        }

        $category = $this->_initCategory(true);

        $block = $this->_view->getLayout()->createBlock('Magento\Catalog\Block\Adminhtml\Category\Tree');
        $root = $block->getRoot();
        $this->getResponse()->representJson(
            $this->_objectManager->get(
                'Magento\Core\Helper\Data'
            )->jsonEncode(
                array(
                    'data' => $block->getTree(),
                    'parameters' => array(
                        'text' => $block->buildNodeName($root),
                        'draggable' => false,
                        'allowDrop' => (bool)$root->getIsVisible(),
                        'id' => (int)$root->getId(),
                        'expanded' => (int)$block->getIsWasExpanded(),
                        'store_id' => (int)$block->getStore()->getId(),
                        'category_id' => (int)$category->getId(),
                        'root_visible' => (int)$root->getIsVisible()
                    )
                )
            )
        );
    }
}
