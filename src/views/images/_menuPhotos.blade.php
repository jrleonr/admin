<div class="col-sm-12" id="menuPhotos">
    <div id="upload-button" class="pull-left">
        <div class="pull-left">
            <span id="upload-button" class="btn btn-primary fileinput-button">
                <span>{{ trans('edit.addPhoto') }}</span>
                <input id="fileupload" type="file" name="image" data-url="{{ route('admin.images.store') }}" >
            </span>
        </div>
    </div>
</div>

