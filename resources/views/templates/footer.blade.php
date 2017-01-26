<!-- footer content -->
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		Made with <i class="fa fa-heart fa-lg" style="color: #d90429;"></i> by 
		<strong>
			<a href="https://www.facebook.com/mr.multitalented" target="_blank" style="color: #676a6c;">Akhilesh Darjee</a>
		</strong>
	</div>
	<strong>Origin CMS</strong> - {{ date('Y') }}
</footer>
<!-- /footer content -->
@include('templates.msgbox')
@if (Session::has('msg'))
	<script type="text/javascript">
		msgbox("{{ Session::get('msg') }}");
	</script>
@endif
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="{{ elixir('js/all.js') }}"></script>