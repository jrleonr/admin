<?php 

namespace Pingpong\Admin\Controllers;

use Illuminate\Http\Request;
use Pingpong\Admin\Uploader\ImageUploader;
use Pingpong\Admin\Repositories\Images\ImageRepository;
use Pingpong\Admin\Repositories\Articles\ArticleRepository;

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


    public function store(Request $request, ArticleRepository $article)
    {
        $data = $request->all();

        //$image = $image->newPhoto();
        unset($data['image']);

        if (\Input::hasFile('image')) {
            // upload image
            $this->uploader->upload('image')->save('images/articles');

            $data['url'] = $this->uploader->getFilename();

            $data['user_id'] = \Auth::id();

            $data['post_id'] = $data['article_id'];

            $image = $this->repository->create($data);

            $article = $article->findById($data['article_id']);

            if (!isset($article->image)) {
                $article->update(['image' => $data['url']]);
            }


            return ['id' => $image->id, 'url' => asset('images/articles/150/' . $data['url']), 'bigUrl' => asset('images/articles/400/' . $data['url']), 'main' => $image->main];
        }
    }

    /**
     * @param Request $request
     * @param Filesystem $filesystem
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $this->uploader->delete($request->input('id'));
            $this->repository->delete($request->input('id'));

        }
    }

    //name??
    public function update(Request $request)
    {
        $this->uploader->setMain($request->input('id'));
    }
}
