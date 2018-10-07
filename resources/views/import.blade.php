<form method="post" action="{{ route('uploadExcel') }}" enctype="multipart/form-data">
	{{ csrf_field() }}

	<input type="file" name="excelFile">
	@if ($errors->has('excelFile'))
	    <div class="error">{{ $errors->first('excelFile') }}</div>
	@endif
	<input type="submit" name="upload" value="Upload">
</form>