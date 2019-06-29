<?php
namespace Packt\HelloWorld\Block\Adminhtml\Tab;
use Magento\Cms\Block\Adminhtml\Page\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use mysql_xdevapi\Exception;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Delete Employee'),
            'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this employee ?') . '\', \'' . $this->getDeleteUrl() . '\')',
            'class' => 'delete',
            'sort_order' => 20
        ];
    }

    public function getDeleteUrl()
    {
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $url = $urlInterface->getCurrentUrl();

        $parts = explode('/', parse_url($url, PHP_URL_PATH));
//        I made dis
        for($i=0; $i< count($parts);$i++)
            $tmp = $i;
        $id = $parts[$tmp];

        return $this->getUrl('*/*/delete', ['id' => $id]);
    }
}