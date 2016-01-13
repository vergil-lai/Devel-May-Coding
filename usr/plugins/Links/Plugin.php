<?php
/**
 * 友情链接插件
 * 
 * @package Links
 * @author Hanny
 * @version 1.0.0
 * @link http://www.imhan.com
 *
 * 历史版本
 *
 * version 1.0.0 at 2009-12-12
 * 实现友情链接的基本功能
 * 包括: 添加 删除 修改 排序
 */
class Links_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
		try {
			Links_Plugin::linksInstall();
			$err = '建立友情链接数据表，插件启用成功';
		} catch (Typecho_Db_Exception $e) {
			$code = $e->getCode();
			if (1050 == $code) {
				$err = '友情链接数据表已经存在，插件启用成功';
			} else {
				return _t('友情链接插件启用失败');
			}
		}
		Helper::addPanel(3, 'Links/manage-links.php', '友情链接', '', 'administrator');
		Helper::addAction('links-edit', 'Links_Action');
		return _t($err);
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
	{
		Helper::removeAction('links-edit');
		Helper::removePanel(3, 'Links/manage-links.php');
	}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

	public static function linksInstall()
	{
		$installDb = Typecho_Db::get();
		$scripts = file_get_contents('usr/plugins/Links/Mysql.sql');
		$scripts = str_replace('%charset%', 'utf8', $scripts);
		$scripts = explode(';', $scripts);
		foreach ($scripts as $script)
		{
			$script = trim($script);
			if ($script)
			{
				$installDb->query($script, Typecho_Db::WRITE);
			}
		}
	}

	public static function form($action = NULL)
	{
        /** 构建表格 */
		$options = Typecho_Widget::widget('Widget_Options');
        $form = new Typecho_Widget_Helper_Form(Typecho_Common::url('/action/links-edit', $options->index),
        Typecho_Widget_Helper_Form::POST_METHOD);
        
        /** 链接名称 */
        $name = new Typecho_Widget_Helper_Form_Element_Text('name', NULL, NULL, _t('链接名称*'));
        $form->addInput($name);
        
        /** 链接地址 */
        $url = new Typecho_Widget_Helper_Form_Element_Text('url', NULL, "http://", _t('链接地址*'));
        $form->addInput($url);
        
        /** 链接描述 */
        $description =  new Typecho_Widget_Helper_Form_Element_Textarea('description', NULL, NULL, _t('链接描述'));
        $form->addInput($description);
        
        /** 链接动作 */
        $do = new Typecho_Widget_Helper_Form_Element_Hidden('do');
        $form->addInput($do);
        
        /** 链接主键 */
        $lid = new Typecho_Widget_Helper_Form_Element_Hidden('lid');
        $form->addInput($lid);
        
        /** 提交按钮 */
        $submit = new Typecho_Widget_Helper_Form_Element_Submit();
        $form->addItem($submit);
		$request = Typecho_Request::getInstance();

        if (isset($request->lid) && 'insert' != $action) {
            /** 更新模式 */
			$db = Typecho_Db::get();
            $link = $db->fetchRow($db->select()->from('typecho_links')->where('lid = ?', $request->lid));
            if (!$link) {
                throw new Typecho_Widget_Exception(_t('链接不存在'), 404);
            }
            
            $name->value($link['name']);
            $url->value($link['url']);
            $description->value($link['description']);
            $do->value('update');
            $lid->value($link['lid']);
            $submit->value(_t('编辑链接'));
            $_action = 'update';
        } else {
            $do->value('insert');
            $submit->value(_t('增加链接'));
            $_action = 'insert';
        }
        
        if (empty($action)) {
            $action = $_action;
        }

        /** 给表单增加规则 */
        if ('insert' == $action || 'update' == $action) {
            $name->addRule('required', _t('必须填写链接名称'));
			$url->addRule('required', _t('必须填写链接地址'));
			$url->addRule('url', _t('不是一个合法的链接地址'));
        }
        if ('update' == $action) {
            $lid->addRule('required', _t('链接主键不存在'));
            $lid->addRule(array(new Links_Plugin, 'LinkExists'), _t('链接不存在'));
        }
        return $form;
	}

	public static function LinkExists($lid)
	{
		$db = Typecho_Db::get();
		$link = $db->fetchRow($db->select()->from('typecho_links')->where('lid = ?', $lid)->limit(1));
		return $link ? true : false;
	}

	public static function output($pattern=NULL)
	{
		if ($pattern == NULL) {
			$pattern = "<li><a href={url} target=\"_blank\">{name}</a></li>\n";
		}
		$db = Typecho_Db::get();
		$links = $db->fetchAll($db->select()->from('typecho_links')->order('typecho_links.order', Typecho_Db::SORT_ASC));
		foreach ($links as $link) {
			echo str_replace(array('{url}', '{name}'), array($link['url'], $link['name']), $pattern);
		}
	}
}
