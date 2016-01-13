<?php

function themeConfig($form) {
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点LOGO地址'), _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO'));
    $form->addInput($logoUrl);
    $weiboAccount = new Typecho_Widget_Helper_Form_Element_Text('weiboAccount', NULL, NULL, _t('新浪微博UID'), _t('在这里填入一个新浪微博帐号, 然后即可在侧栏显示新浪微博'));
    $form->addInput($weiboAccount);
    
    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('sidebarBlock', 
    array(
    'ShowRecentPosts' => _t('显示最新文章'),
    'ShowRelatedPosts' => _t('显示相关文章'), 
    'ShowRecentComments' => _t('显示最近回复'), 
    'ShowTags' => _t('显示标签'), 
    'ShowCategory' => _t('显示分类'),
    'ShowArchive' => _t('显示归档'),
    'ShowLinks' => _t('显示友情链接(需要友情链接插件)'),
    'ShowOther' => _t('显示其它杂项')),
    array('ShowRelatedPosts', 'ShowRecentComments', 'ShowTags', 'ShowArchive', 'ShowOther'), _t('侧边栏显示'));
    
    $form->addInput($sidebarBlock->multiMode());
}
