
<?php  if (isset($_GET['embed'])) { ?>


<?php } else { ?>
</div>
	<!-- footer -->
	<div id="footer">

	
	

		<!-- footer content -->
		<div class="wrapper-960">
			<div id="footer-top"></div> <!-- footer-top end -->
				
				<a class="footer-btn" title="<?php echo Kohana::lang('ui_main.footer-contact-tooltip'); ?>" id="footer-btn-contact" href="<?php echo url::site();?>contact"></a>
				
				<a class="footer-btn" title="<?php echo Kohana::lang('ui_main.footer-spread-tooltip'); ?>" id="footer-btn-spread" data-reveal-id="popup-spread" href="#"></a>
				
				<a class="footer-btn" title="<?php echo Kohana::lang('ui_main.footer-copyleft-tooltip'); ?>" id="footer-btn-license" data-reveal-id="popup-copyleft" href="#"></a>
				
				<a class="footer-btn" title="<?php echo Kohana::lang('ui_main.footer-help-tooltip'); ?>" id="footer-btn-help" data-reveal-id="popup-help" href="#"></a>

				
				<?php 
		$search = "<div class=\"search-form\">";
		$search .= form::open("search", array('method' => 'get', 'id' => 'search'));
		$search .= "<ul>";
		$search .= "<li><input type=\"text\" name=\"k\" placeholder=\"".Kohana::lang('ui_main.search')."\" value=\"\" class=\"text\" /></li>";
		$search .= "<li><input type=\"submit\" name=\"b\" class=\"searchbtn\" value=\"".Kohana::lang('ui_main.search')."\" /></li>";
		$search .= "</ul>";
		$search .= form::close();
		$search .= "</div>";

		echo $search;
?>
				
				<div title="<?php echo Kohana::lang('ui_main.footer-tt-lang'); ?>" id="footer-lang">
					<?php $lang=Kohana::config('locale.language.0')?>
					<a class="<?php if ($lang == "es_AR") {echo "lang-active";}?>" href="?l=es_AR">ES</a>
					<a class="<?php if ($lang == "en_US") {echo "lang-active";}?>" href="?l=en_US">EN</a>
					<a class="<?php if ($lang == "pt_PT") {echo "lang-active";}?>" href="?l=pt_PT">PT</a>					
					<a class="<?php if ($lang == "ca_ES") {echo "lang-active";}?>" href="?l=ca_ES">CA</a>					
				</div>
				
				
				<a id="footer-btn-ds" title="<?php echo Kohana::lang('ui_main.tt-diaspora'); ?>" class="footer-btn share-btn"  href="#"
    onclick="return !window.open('http://sharetodiaspora.github.io/?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title),'das','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=550')"
    target="_blank"></a>

    	<a class="footer-btn share-btn" title="<?php echo Kohana::lang('ui_main.tt-facebook'); ?>" id="footer-btn-fb"  href="#"
    onclick="return !window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href)+'','das','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=550')"
    target="_blank"></a>
 
			<a class="footer-btn share-btn" title="<?php echo Kohana::lang('ui_main.tt-twitter'); ?>" id="footer-btn-tw" onclick="return !window.open('http://twitter.com/home/?status='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title),'das','location=no,links=no,scrollbars=no,toolbar=no,width=620,height=550')" href="#"></a>


		<!-- / footer content -->

		
		
	</div>
	<!-- / footer -->

	<?php
	echo $footer_block;
	// Action::main_footer - Add items before the </body> tag
	Event::run('ushahidi_action.main_footer');
	?>
	
	</div>
	<!-- / wrapper -->

	<script type="text/javascript">
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>
	
	
	<!-- Popups -->
	<div id="popup-spread" class="reveal-modal"><?php echo Kohana::lang('ui_main.footer-spread-popup'); ?>
		
		
		<textarea onClick="SelectAll('embed-textarea');" readonly id="embed-textarea" dir="ltr">&lt;iframe name='reevo_map' src='http://map.reevo.org/?embed=&l=<?php echo Kohana::config('locale.language.0'); ?>' width="540" height="340" frameborder="0"&gt;&lt;/iframe&gt;</textarea>
	
	</div>
	
	<div id="popup-copyleft" class="reveal-modal"><?php echo Kohana::lang('ui_main.footer-copyleft-popup'); ?></div>

	<div id="popup-help" class="reveal-modal"><?php echo Kohana::lang('ui_main.frontpage-help'); ?></div>

	<div id="popup-contact" class="reveal-modal">
	<?php echo Kohana::lang('ui_main.frontpage-help'); ?>
	</div>	
	
<!-- 	Scripts -->

<script type="text/javascript">
function popup(meh)
{
    var x = screen.width/2 - 700/2;
    var y = screen.height/2 - 450/2;
    window.open(meh.href, 'sharegplus','height=485,width=700,left='+x+',top='+y);
}
</script>

<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + 
"://webstats.reevo.org/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 3]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; 
g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="http://webstats.reevo.org/piwik.php?idsite=3" style="border:0;" 
alt="" /></p></noscript>
<!-- End Piwik Code -->
	
	
</body>
</html>
<?php } ?>
