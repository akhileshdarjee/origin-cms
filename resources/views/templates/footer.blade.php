<!-- footer content -->
<footer>
	<div class="pull-right">
		Sharingan CMS {{ date('Y') }}</a>
	</div>
	<div class="clearfix"></div>
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