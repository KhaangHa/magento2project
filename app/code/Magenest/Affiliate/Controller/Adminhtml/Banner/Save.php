<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 10/03/2018
 * Time: 14:50
 */

namespace Magenest\Affiliate\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;


class Save extends Action
{
    protected $adapterFactory;

    protected $filesystem;

    protected $uploaderFactory;

    protected $bannerFactory;

    public function __construct(
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        Action\Context $context,
        \Magenest\Affiliate\Model\BannerFactory $bannerFactory
    )
    {
        $this->adapterFactory = $adapterFactory;
        $this->uploaderFactory = $uploaderFactory;

        $this->filesystem = $filesystem;
        $this->bannerFactory = $bannerFactory;
        parent::__construct($context);

    }


    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $resultRedirect = $this->resultRedirectFactory->create();
            try {
                $model = $this->bannerFactory->create();
                if(isset($data['image']) && is_array($data['image'])){
                   $data['image'] = $data['image'][0]['url'];
                }else if(!isset($data["image"])){
                        $this->messageManager->addError(__('Image is required'));
                        $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                        return $resultRedirect->setPath('*/*/');
                }
                if(isset($data['general'])){
                    $data['id'] = $data['general']['id'];
                }
                if (!empty($data['id'])) {
                    $model->load($data['id']);
                    if ($data['id'] != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('Wrong mapping rule.'));
                    }
                }
                else unset($data["id"]); //remove id element so it will generate automatically

                $model->setData($data);
//                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());

                $model->save();
                $this->messageManager->addSuccess(__('The banner has been saved.'));
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
//        $resultRedirect = $this->resultRedirectFactory->create();
//        $data = $this->getRequest()->getPostValue();
//
//        if ($data) {
//            try {
//
//                $contact = $this->bannerFactory->create();
//                $data["image"] = $data["image"][0]["url"];
//                $data = array_filter($data, function ($value) {
//                    return $value !== '';
//                });
//
//                if ($data) {
//                    $model = $this->bannerFactory->create();
//                    $id = $data["id"];
//                    if ($id) {
//                        $model->load($id);
//                        if ($id != $model->getId()) {
//                            throw new \Magento\Framework\Exception\LocalizedException(__('Wrong mapping rule.'));
//                        }
//                    }
//                }
//
//                $contact->setData($data);
//
//                $contact->save();
//                $this->messageManager->addSuccess(__('Successfully saved.'));
//                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
//                return $resultRedirect->setPath('*/*/');
//            } catch (\Exception $e) {
//                $this->messageManager->addError($e->getMessage());
//                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
//                return $resultRedirect->setPath('*/*/', ['id' => $contact->getId()]);
//            }
//        }
//
//        return $resultRedirect->setPath('*/*/');
    }


}



