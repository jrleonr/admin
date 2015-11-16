<div id="menuPhotos">
    <div id="upload-button" class="pull-left">
        <div class="pull-left">
            <span id="upload-button" class="btn btn-primary fileinput-button">
                {!! Form::label('image', 'SUBIR FOTO') !!}
                <input id="fileupload" type="file" name="image" data-url="{{ route('admin.images.store') }}" >
            </span>
        </div>
    </div>
</div>

