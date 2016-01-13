<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="main">
    <div class="body body-950">
        <?php include 'page-title.php'; ?>
        <div class="container typecho-page-main manage-metas">

                <div class="column-16 suffix">
                    <ul class="typecho-option-tabs">
                        <li class="current"><a href="<?php $options->adminUrl('extending.php?panel=Links%2Fmanage-links.php'); ?>"><?php _e('友情链接'); ?></a></li>
                    </ul>
                    <?php
						$installDb = Typecho_Db::get();
						$links = $db->fetchAll($db->select()->from('typecho_links')->order('typecho_links.order', Typecho_Db::SORT_ASC));
                    ?>
                    <form method="post" name="manage_categories" class="operate-form" action="<?php $options->index('/action/links-edit'); ?>">
                    <div class="typecho-list-operate">
                        <p class="operate"><?php _e('操作'); ?>: 
                            <span class="operate-button typecho-table-select-all"><?php _e('全选'); ?></span>, 
                            <span class="operate-button typecho-table-select-none"><?php _e('不选'); ?></span>&nbsp;&nbsp;&nbsp;
                            <?php _e('选中项'); ?>: 
                            <span rel="delete" lang="<?php _e('你确认要删除这些链接吗?'); ?>" class="operate-button operate-delete typecho-table-select-submit"><?php _e('删除'); ?></span>
                        </p>
                    </div>
                    
                    <table class="typecho-list-table draggable">
                        <colgroup>
                            <col width="25"/>
                            <col width="200"/>
                            <col width="345"/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="typecho-radius-topleft"> </th>
                                <th><?php _e('名称'); ?></th>
                                <th><?php _e('网址'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($links)): $alt = 0;?>
                            <?php foreach ($links as $link): ?>
                            <tr<?php if ($alt == 0) {echo ' class="even"'; $alt = 1;} else {$alt = 0;} ?> id="link-<?php echo $link['lid'] ?>">
                                <td><input type="checkbox" value="<?php echo $link['lid']; ?>" name="lid[]"/></td>
                                <td><a href="<?php echo $request->makeUriByRequest('lid=' . $link['lid']); ?>"><?php echo $link['name']; ?></a></td>
                                <td><?php echo $link['url']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr class="even">
                                <td colspan="3"><h6 class="typecho-list-table-title"><?php _e('没有任何链接'); ?></h6></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="do" value="delete" />
                    </form>
                </div>

                <div class="column-08 typecho-mini-panel typecho-radius-topleft typecho-radius-topright typecho-radius-bottomleft typecho-radius-bottomright">
						<?php Links_Plugin::form()->render(); ?>
                </div>

        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script type="text/javascript">
    (function () {
        window.addEvent('domready', function() {
            var _selection;
            
            <?php if (isset($request->mid)): ?>
            var _hl = $(document).getElement('.typecho-mini-panel');
            if (_hl) {
                _hl.set('tween', {duration: 1500});
    
                var _bg = _hl.getStyle('background-color');
                if (!_bg || 'transparent' == _bg) {
                    _bg = '#F7FBE9';
                }

                _hl.tween('background-color', '#AACB36', _bg);
            }
            <?php endif; ?>
            
            if ('tr' == Typecho.Table.table._childTag) {
                Typecho.Table.dragStop = function (obj, result) {
                    var _r = new Request.JSON({
                        url: '<?php $options->index('/action/links-edit'); ?>'
                    }).send(result + '&do=sort');
                };
            } else {
                Typecho.Table.checked = function (input, item) {
                    if (!_selection) {
                        _selection = document.createElement('div');
                        $(_selection).addClass('tag-selection');
                        $(_selection).addClass('clearfix');
                        $(document).getElement('.typecho-mini-panel form')
                        .insertBefore(_selection, $(document).getElement('.typecho-mini-panel form #typecho-option-item-name-0'));
                    }
                    
                    var _href = item.getElement('span').getProperty('rel');
                    var _text = item.getElement('span').get('text');
                    var _a = document.createElement('a');
                    $(_a).addClass('button');
                    $(_a).setProperty('href', _href);
                    $(_a).set('text', _text);
                    _selection.appendChild(_a);
                    item.checkedElement = _a;
                };
                
                Typecho.Table.unchecked = function (input, item) {
                    if (item.checkedElement) {
                        $(item.checkedElement).destroy();
                    }
                    
                    if (!$(_selection).getElement('a')) {
                        _selection.destroy();
                        _selection = null;
                    }
                };
            }
        });
    })();
</script>
<?php include 'footer.php'; ?>
