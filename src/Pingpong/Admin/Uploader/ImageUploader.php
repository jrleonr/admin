<?php

namespace Pingpong\Admin\Uploader;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Pingpong\Admin\Entities\Image as Model;
use Pingpong\Admin\Entities\Article;

class ImageUploader
{
    /**
     * @var string
     */
    protected $ext = '.jpg';

    /**
    * @var string
    */
    private $path = 'images/articles';

    /**
     * @param $ext
     *
     * @return $this
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getRandomFilename()
    {
        return sha1(str_random()).$this->getExt();
    }

    /**
     * @return string
     */
    public function getDestinationFile()
    {
        return public_path(str_finish($this->path, '/').$this->filename);
    }

    /**
     * @param $width
     *
     * @return $this
     */
    public function widen($width)
    {
        $this->image->widen($width);

        return $this;
    }

    /**
     * @param $file
     *
     * @return $this
     */
    public function upload($file)
    {
        $this->filename = $this->getRandomFilename();
        $this->image = Image::make(Input::file($file)->getRealPath());

        return $this;
    }


    public function getDestinationDirectory()
    {
        return dirname($this->getDestinationFile());
    }

    /**
     * @param null $path
     *
     * @return mixed
     */
    public function save($path = null)
    {
        if (!is_null($path)) {
            $this->path = $path;
        }

        if (!is_dir($path = $this->getDestinationDirectory())) {
            File::makeDirectory($path, 0777, true);
        }

        //$this->image->save($this->getDestinationFile());

        $this->resize($path, $this->filename);

        return $this->filename;
    }

    /**
    *
    **/
    public function resize($path, $filename)
    {

        $this->createDirectories($path);

        $image = $this->image;

        foreach(config('admin.image.resize') AS $size)
        {
            $image->resize(null,$size, function ($c)
            {
                $c->aspectRatio();
            })->save($path . "/$size/{$filename}");
        }

        $image = $this->image;

        foreach(config('admin.image.cut') AS $width => $height)
        {
            $image->fit($width,$height)->save($path ."/{$height}/{$filename}");

        }

        $image = $this->image;

        foreach(config('admin.image.fit') AS $size)
        {
            $image->fit($size)->save($path ."/{$size}/{$filename}");

        }

    }


    public function delete($id, $path = 'images/articles')
    {
        $user = \Auth::user();

        $image = Model::whereRaw('id = ? and user_id = ?', [$id, $user->id])->firstOrFail();

        foreach (array_merge(config('admin.image.resize'),config('admin.image.fit'),config('admin.image.cut')) AS $size)
        {
            \Storage::disk('local')->delete("{$path}/{$size}/{$image->url}");
        }

        $image->delete();


        if($image->main)
        {

            $article = Article::where('id','=', $image->article_id )->firstOrFail();

            if ($newMainImage = Image::where('post_id', '=', $image->article_id )->first() )
            {
                $newMainImage->main = 1;
                $newMainImage->save();

                $article->image = $newMainImage->url;

            }
            else
            { 
                $article->image = null;
            }

            $article->save();
        }
    }

    public function setMain($id)
    {
        $image = Model::where('id','=', $id)->firstOrFail();

        Model::where('post_id', '=', $image->post_id)->update(['main' => 0]);
        $article = Article::where('id','=', $image->post_id )->firstOrFail();


        $image->main = 1;
        $image->save();

        $article->image = $image->url;
        $article->save();
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
    * Create Directories
    *
    * 
    */
    public function createDirectories($path)
    {
        foreach(array_merge(config('admin.image.resize'),config('admin.image.fit'),config('admin.image.cut')) AS $dir)
        {
            if (!File::exists($path .'/'. $dir )) 
            {

                File::makeDirectory($path .'/'. $dir , 0777, true);
            }
        }
    }

    public function getUrl($size, $name = null)
    {
        if(!$name)
            return asset("img/unknown.jpg");

        //return Config::get('filesystems.disks.s3.endpoint') . $size .'/'.$name;

        return asset($this->path . '/'. $size .'/'.$name);
    }

    public function getUrls($images, $size, $user_id = null, $bigSize = '400', $medSize = '263', $smallSize = '150')
    {

        foreach($images as $image)
        {
            $image['bigUrl'] = self::getUrl($bigSize, $image['url']);
            $image['medUrl'] = self::getUrl($medSize, $image['url']);
            $image['smallUrl'] = self::getUrl($smallSize, $image['url']);

            $image['url'] = self::getUrl($size, $image['url']);

        }

        if(empty($images))
            $urls[]['url'] = self::getUrl($size);
        
        return $images;
    }

}