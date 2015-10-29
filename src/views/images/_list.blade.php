<div id="images" >

@forelse( $images AS $image)
    <div id="{{ $image['id']}}" class="photo" >

        <img class="img-responsive" src="{{$image['url']}}">

        <div class="star">
            <div class="star-default @if($image['main']) hidden @endif " onclick="setMain({{$image['id']}})"></div>
            @if($image['main'])
                <div id="star"></div>
            @endif
        </div>


        <div onclick="deletePhoto({{  $image['id'] }})" type="submit" class="btn btn-danger delete">X</div>
     
    </div>

@empty


 <div id="photoUpload" class="col-sm-9 col-sm-push-1 text-center">
    <i class="fa fa-camera fa-3x"></i>
    <div class="h3" style="">{{ trans('edit.uploadPhotosOfYou') }}</div>
    <p class="text-muted">{{ trans('edit.uploadPhotosOfYouDescription') }} </p>
</div>





@endforelse

</div>


