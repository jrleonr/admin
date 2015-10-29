<?php 

namespace Pingpong\Admin\Controllers;

use Illuminate\Http\Request;
use Pingpong\Admin\Uploader\ImageUploader;
use Pingpong\Admin\Repositories\Images\ImageRepository;

class ImagesController extends BaseController
{
    private $uploader;
    private $repository;
    private $user;

    public function __construct(ImageUploader $uploader, ImageRepository $repository)
    {
        $this->uploader = $uploader;
        $this->repository = $repository;
    }


    public function store(Request $request)
    {
        $data = $request->all();

        //$image = $image->newPhoto();
        unset($data['image']);

        if (\Input::hasFile('image')) {
            // upload image
            $this->uploader->upload('image')->save('images/articles');

            $data['url'] = $this->uploader->getFilename();

            $data['user_id'] = \Auth::id();

            $image = $this->repository->create($data);


            return ['id' => $image->id, 'url' => asset('images/articles/' . $data['url'] ), 'main' => $image->main];
        }
    }

    /**
     * @param Request $request
     * @param Filesystem $filesystem
     */
    public function destroy(Request $request, Images $image)
    {
        if ($request->ajax()) {
            $image->delete($request->input('id'));
        }
    }

    //name??
    public function update(Request $request, Images $image)
    {
        $image->setMain($request->input('id'));
    }
}
