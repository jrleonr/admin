<div class="col-sm-8">
@if(isOnPages())
	@if(isset($model))
	{!! Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.pages.update', $model->id]]) !!}
	@else
	{!! Form::open(['files' => true, 'route' => 'admin.pages.store']) !!}
	@endif
@else
	@if(isset($model))
	{!! Form::model($model, ['method' => 'PUT', 'files' => true, 'route' => ['admin.articles.update', $model->id]]) !!}
	@else
	{!! Form::open(['files' => true, 'route' => 'admin.articles.store']) !!}
	@endif
@endif	
	<div class="form-group">
		{!! Form::label('title', 'Title:') !!}
		{!! Form::text('title', null, ['class' => 'form-control']) !!}
		{!! $errors->first('title', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::label('slug', 'Slug:') !!}
		{!! Form::text('slug', null, ['class' => 'form-control']) !!}
		{!! $errors->first('slug', '<div class="text-danger">:message</div>') !!}
	</div>
	@if(! isOnPages())
	<div class="form-group">
		{!! Form::label('category_id', 'Category:') !!}
		{!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
		{!! $errors->first('category_id', '<div class="text-danger">:message</div>') !!}
	</div>
	@else
	{!! Form::hidden('type', 'page') !!}
	@endif
	<div class="form-group">
		{!! Form::label('body', 'Body:') !!}
		{!! Form::textarea('body', null, ['class' => 'form-control', 'id' => 'editor']) !!}
		{!! $errors->first('body', '<div class="text-danger">:message</div>') !!}
	</div>
	<div class="form-group">
		{!! Form::submit(isset($model) ? 'Update' : 'Save', ['class' => 'btn btn-primary']) !!}
	</div>
{!! Form::close() !!}
</div>

<div class="col-sm-4">
	@if(isset($model))
		@include('admin::images._list', ['images' => $images])
	@endif
</div>

@section('style')   
	{!! style('vendor/summernote/summernote.css') !!}
@stop

@section('script')
	
	{!! script('vendor/ckeditor/ckeditor.js') !!}

	@if(isset($model))
		{!! script('js/upload.js') !!}
    	@include('admin::images._js')
    @endif

	<script type="text/javascript">
		CKEDITOR.replace( 'editor' );
		CKEDITOR.config.allowedContent = true;
	</script>
@stop
