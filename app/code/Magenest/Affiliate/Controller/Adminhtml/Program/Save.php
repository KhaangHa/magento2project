<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 10/03/2018
 * Time: 14:50
 */

namespace Magenest\Affiliate\Controller\Adminhtml\Program;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\Context;
use Magenest\Affiliate\Model\Program;
use Magenest\Affiliate\Model\ProgramSale;

class Save extends Action
{
    protected $adapterFactory;

    protected $filesystem;

    protected $uploaderFactory;

    protected $_programFactory;

    protected $_configFactory;

    protected $_currentMaxDataTier;

    protected $_programSaleFactory;

    protected $connection;
    protected $resource;

    public function __construct(
        Action\Context $context,
        \Magenest\Affiliate\Model\ProgramFactory $programFactory,
        \Magenest\Affiliate\Model\ProgramConfigFactory $programTypeFactory,
        \Magenest\Affiliate\Model\ProgramSaleFactory $programSaleFactory,
        \Magento\Framework\App\ResourceConnection $resource
    )
    {

        $this->_programFactory = $programFactory;
        $this->_configFactory = $programTypeFactory;
        $this->_programSaleFactory = $programSaleFactory;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        parent::__construct($context);

    }

    public function execute()
    {
        /**@
         *
         * GET DATA FROM POST
         * CHECK DATA IS EXIST
         * CHECK 2 ADDITION FIELDS
         * CHECK CONFIG OPTIONS
         * --> CHOOSE WHICH FIELD TO BE USE
         * --> IF TYPE = SALE THEN USE SALE MODEL
         * --> ELSE DO THE OPOSITE
         *
         *
         */

        /*STEP 1*/
        $data = $this->getRequest()->getPostValue();
        $isSale = false; //FLAG FOR SALE
        $nextId = $this->getNextAutoincrement('magenest_affiliate_program');
        /*STEP 2*/
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_programFactory->create();

            /*STEP 3*/
            if ($data['program_type_id'] == Program::TYPE_PAY_PER_SALE) {
                $isSale = true;
                if (isset($data['dynamic_row_sale'])) {
                    $saleData = $data['dynamic_row_sale'];
                }
                //3.1 CREATE SALE MODEL
                $configModel = $this->_programSaleFactory->create();
                /*
                 * CHECK EXIST DATA (No = exit/ Yes = Continue)
                 * ADD ADDITIONAL INFO TO DATA VAR (ex. program_id, program_type_id, etc...)
                 * INSERT MULTIPLE LINE DATA TO TABLE
                 * CHECK IF NUMBER OF TIER IN DB > CURRENT TIER (WHICH MEAN THERE ARE DELETED TIER(S))
                 * --yes--> DELETE SPARE TIER(S)
                 *
                 */

                //STEP 1
                if (isset($saleData)) {
                    $n = count($saleData);
                    //STEP 2
                    for ($i = 0; $i < $n; $i++) {
                        if (isset($saleData['id']) && isEmpty($saleData['id'])) // IF THERE IS EXISTED EMPTY ID => DELETE IT SO SYSTEM CAN AUTO GENERATE IT
                            unset($saleData['id']);
                        //ADD ADDITIONAL DATA(S)
                        $saleData[$i]['program_type_id'] = $data['program_type_id'];
                        if (isset($data['id']) && !empty($data['id']))
                            $saleData[$i]['program_id'] = $data['id'];
                        else $saleData[$i]['program_id'] = $nextId;
                    }
                    //STEP 3
                    foreach ($saleData as $config) {
                        try {
                            $this->insertMultiple($configModel, $config, $isSale);
                        } catch (\Exception $e) {
                            $this->connection->rollBack();
                        }
                    }
                    //STEP 4
                    if ($this->countMaxTier($isSale) > $n) {
                        $this->deleteConfig($isSale);
                    }

                }

            } else {
                if (isset($data['dynamic_rows_container'])) {
                    $configData = $data['dynamic_rows_container'];
                }
                //3.2 CREATE CONFIG MODEL
                $configModel = $this->_configFactory->create();
                /*
                 * CHECK EXIST DATA (No = exit/ Yes = Continue)
                 * ADD ADDITIONAL INFO TO DATA VAR (ex. program_id, program_type_id, etc...)
                 * INSERT MULTIPLE LINE DATA TO TABLE
                 * CHECK IF NUMBER OF TIER IN DB > CURRENT TIER (WHICH MEAN THERE ARE DELETED TIER(S))
                 * --yes--> DELETE SPARE TIER(S)
                 *
                 */

                //STEP 1
                if (isset($configData)) {
                    $n = count($configData);
                    //STEP 2
                    for ($i = 0; $i < $n; $i++) {
                        if (isset($configData['id']) && isEmpty($configData['id']))
                            unset($configData['id']);
                        $configData[$i]['program_type_id'] = $data['program_type_id'];
                        if (isset($data['id']) && !empty($data['id']))
                            $configData[$i]['program_id'] = $data['id'];
                        else $configData[$i]['program_id'] = $nextId;
                    }
                    //STEP 3
                    foreach ($configData as $config) {
                        try {
                            $this->insertMultiple($configModel, $config, $isSale);
                        } catch (\Exception $e) {
                            $this->connection->rollBack();
                        }
                    }
                    //STEP 4
                    if ($this->countMaxTier($isSale) > $n) {
                        $this->deleteConfig($isSale);
                    }

                }
            }
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
                if ($id != $model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Wrong mapping rule.'));
                }
            } else unset($data['id']);

            $model->setData($data);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The campaign has been saved.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the mapping.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }


    public function countMaxTier($isSale)
    {
        /*
         * CHECK IS SALE --true--> use sale collection
         *              \__false--> use config collection
         */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if($isSale)
        {
            $collection = $objectManager
                ->create('Magenest\Affiliate\Model\ResourceModel\ProgramSale\Collection')
                ->addFieldToFilter('program_id', $this->getRequest()->getParam('id'))->getData();
        }
        else{
            $collection = $objectManager
                ->create('Magenest\Affiliate\Model\ResourceModel\ProgramConfig\Collection')
                ->addFieldToFilter('program_id', $this->getRequest()->getParam('id'))->getData();
        }

        $count = 0;
        foreach ($collection as $item) {
            $count = $item['tier'];
        }
        return $count;
    }

    public function updateMultiple($model, $config, $isSale)
    {
        /*
         * CHECK IS SALE --true--> use sale collection
         *              \__false--> use config collection
         */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if($isSale)
        {
            $collection = $objectManager
                ->create('Magenest\Affiliate\Model\ResourceModel\ProgramSale\Collection')
                ->addFieldToFilter('program_id', $this->getRequest()->getParam('id'))->getData();
        }
        else{
            $collection = $objectManager
                ->create('Magenest\Affiliate\Model\ResourceModel\ProgramConfig\Collection')
                ->addFieldToFilter('program_id', $this->getRequest()->getParam('id'))->getData();
        }

        //IF THIS -> TIER = DATABASE TIER & THIS -> program_id SAME THEN UPDATE INSTEAD OF CREATE NEW
        $flag = false;
        foreach ($collection as $item) {
            //1 data go with 1 item, item will check all tier from 1 program
            if ($item['tier'] === $config['tier'] && $item['program_id'] === $config['program_id']) {
                //get model if already available id and tier
                $model->load($item['id'], 'id');
                // keep id to edit not to save
                $config['id'] = $model->getId();
                $model->setData($config);
                $model->save();
                $flag = true;
                $this->_currentMaxDataTier = $config['tier'];
            }
        }
        return $flag;
    }

    public function deleteConfig($isSale)
    {
        /*
         * CHECK IS SALE --true--> use sale collection
         *              \__false--> use config collection
         */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if($isSale)
        {
            $collection = $objectManager
                ->create('Magenest\Affiliate\Model\ResourceModel\ProgramSale\Collection')
                ->addFieldToFilter('program_id', $this->getRequest()->getParam('id'))->getData();
        }
        else{
            $collection = $objectManager
                ->create('Magenest\Affiliate\Model\ResourceModel\ProgramConfig\Collection')
                ->addFieldToFilter('program_id', $this->getRequest()->getParam('id'))->getData();
        }
        $max = $this->countMaxTier($isSale);
        $arr = [];
        for ($i = $this->_currentMaxDataTier; $i < $max; $i++) {
            foreach ($collection as $item) {
                if ($item['tier'] == $i + 1) {
                    array_push($arr, $item['id']);
                    break;
                }
            }
        }

        foreach ($arr as $id) {
            if($isSale)
                $model = $this->_programSaleFactory->create()->load($id);
            else
                $model = $this->_configFactory->create()->load($id);
            $model->delete();
        }
    }

    public function insertMultiple($model, $config, $isSale)
    {
        try {
            //test load collection
            // only insert when there are no duplicate values
            $result = $this->updateMultiple($model, $config, $isSale);

            // IF NOT UPDATE THEN CREATE NEW
            if ($result == false) {
                $model->setData($config);
                $model->save();
            }


        } catch (\Exception $e) {
            //Error
        }
    }

    public function getNextAutoincrement($tableName)
    {
        $connection = $this->connection;
        $entityStatus = $connection->showTableStatus($tableName);

        if (empty($entityStatus['Auto_increment'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Cannot get autoincrement value'));
        }
        return $entityStatus['Auto_increment'];
    }

}



