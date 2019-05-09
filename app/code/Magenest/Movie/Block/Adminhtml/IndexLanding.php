<?php

namespace Magenest\Movie\Block\Adminhtml;

use Magento\Framework\View\Element\Template;

class IndexLanding extends Template
{
    public function getMovieUrl()
    {
        return $this->getUrl('movie/index/movie');
    }

    public function getActorUrl()
    {
        return $this->getUrl('movie/index/actor');
    }

    public function getDirectorUrl()
    {
        return $this->getUrl('movie/index/director');
    }

}