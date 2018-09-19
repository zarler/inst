<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/23
 * Time: 下午2:49
 *
 * 分页模板
 */
?><ul class="pagination">
    <?php if ($first_page !== FALSE): ?>
        <li class="paginate_button first" tabindex="0"
            id="datalist_first"><a href="<?php echo HTML::chars($page->url($first_page)) ?>"><?php echo __('首页') ?></a></li>
    <?php else: ?>
        <li class="paginate_button first disabled" tabindex="0"
            id="datalist_first"><a href="javascript:;"><?php echo __('首页') ?></a></li>
    <?php endif ?>

    <?php if ($previous_page !== FALSE): ?>
        <li class="paginate_button previous"  tabindex="0"
            id="datalist_previous"><a href="<?php echo HTML::chars($page->url($previous_page)) ?>"><?php echo __('上页') ?></a></li>
    <?php else: ?>
        <li class="paginate_button previous disabled" tabindex="0"
            id="datalist_previous"><a href="javascript:;"><?php echo __('上页') ?></a></li>
    <?php endif ?>

    <?php if($current_page<=2)
    {
        $first_page = 1;
        $end_page=5;
    }
    else
    {
        $first_page = $current_page-2;
        $end_page = $first_page+4;
    }

    if($end_page>$total_pages)
    {
        $first_page = $total_pages-4;
    }

    if($total_pages<5)
    {
        $first_page = 1;
        $end_page = $total_pages;
    }
    ?>

    <?php for ($i = $first_page; $i <= $end_page; $i++): ?><!--$total_pages 最后页-->
    <?php if ($i == $current_page): ?><!--$current_page 当前页-->
    <li class="paginate_button active" tabindex="0"><a
            href="#"><?php echo $i ?></a></li>
    <?php else: ?>
        <?php if ($i > 0 &&$i <= $total_pages ): ?>
            <li class="paginate_button" tabindex="0"><a href="<?php echo HTML::chars($page->url($i)) ?>"><?php echo $i ?></a>
            </li>
        <?php endif ?>
    <?php endif ?>
    <?php endfor ?>
<?php
/*
 *	<li class="paginate_button disabled" tabindex="0"
		id="datalist_ellipsis"><a href="#">…</a></li>
	<li class="paginate_button " aria-controls="datalist" tabindex="0"><a href="#">10</a>
	</li>
 * */
?>
    <?php if ($next_page !== FALSE): ?>
        <li class="paginate_button next" tabindex="0"
            id="datalist_next"><a href="<?php echo HTML::chars($page->url($next_page)) ?>"><?php echo __('下页') ?></a></li>
    <?php else: ?>
        <li class="paginate_button next disabled" tabindex="0"
            id="datalist_next"><a href="javascript:;"><?php echo __('下页') ?></a></li>
    <?php endif ?>

    <?php if ($last_page !== FALSE): ?>
        <li class="paginate_button last" tabindex="0"
            id="datalist_last"><a href="<?php echo HTML::chars($page->url($last_page)) ?>"><?php echo __('末页') ?></a></li>
    <?php else: ?>
        <li class="paginate_button last disabled" tabindex="0"
            id="datalist_last"><a href="javascript:;"><?php echo __('末页') ?></a></li>
    <?php endif ?>


</ul>

<!-- /pagination -->