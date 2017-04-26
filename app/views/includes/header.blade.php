<header id="top"><a href="<?=URL::to('/main');?>"><img src="<?=URL::to('/'); ?>/_img/logo.svg" /></a>
	<span class="logged-in">{{ Session::get('first_name'); }} {{ Session::get('last_name'); }} - <a href="<?=URL::to('/'); ?>/logout">Logout</a></span>
	@if(!in_array(1, Session::get('dialogs')))
	<div id="warning-top">
		<p>The Insights Dashboard is a performance and analytics tool for internal MediaMath teams seeking information
around competitive intelligence, partner analytics, product usage, spend analytics, and roadmap items.</p>
		<span class="close">x</span>
	</div>
	@endif
</header>
