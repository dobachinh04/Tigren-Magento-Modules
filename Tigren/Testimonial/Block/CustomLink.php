<?php

namespace Tigren\Testimonial\Block;

use Magento\Framework\View\Element\Html\Link;

class CustomLink extends Link
{
    public function _toHtml()
    {
        // Add your custom class to the <li> tag
        $html = '<li class="level0 nav-7 category-item last level-top ui-menu-item">';
        //        $html .= '<a href class="level-top ui-menu-item-wrapper"="' . $this->getUrl($this->getPath()) . '" ' . $this->getLinkAttributes() . '>';
        $html .= '<a href="' . $this->getUrl($this->getPath()) . '" class="level-top ui-menu-item-wrapper" ' . $this->getLinkAttributes() . '>';

        $html .= $this->escapeHtml($this->getLabel()) . '</a></li>';
        return $html;
    }
}
