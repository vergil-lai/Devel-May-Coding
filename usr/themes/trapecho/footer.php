<footer>
    <p>
        Powered by <a href="http://typecho.org/">Typecho</a>. 
        <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254137486'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/stat.php%3Fid%3D1254137486%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
    </p>
</footer><!-- end #footer -->

</div>
</div>

<script src="<?php $this->options->themeUrl('js/jquery-1.7.1.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('js/bootstrap.min.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('js/prettify.js'); ?>"></script>
<script type="text/javascript">
    // fix navbar on scroll
    var $win = $(window)
      , $nav = $('.navbar')
      , navTop = $('.navbar').length && $('.navbar').offset().top - 10
      , isFixed = 0

    processScroll()

    $win.on('scroll', processScroll)

    function processScroll() {
      var i, scrollTop = $win.scrollTop()
      if (scrollTop >= navTop && !isFixed) {
        isFixed = 1
        $nav.addClass('navbar-fixed-top')
      } else if (scrollTop <= navTop && isFixed) {
        isFixed = 0
        $nav.removeClass('navbar-fixed-top')
      }
    }
    
    $('.page-navigator li:not(:has(a))').html('<a>...</a>');
    $('.page-navigator .current').addClass('active');
    
    $('pre').addClass('prettyprint linenums');
    $('.entry-content a').attr('target','_blank');
</script>
<?php $this->footer(); ?>
</body>
</html>