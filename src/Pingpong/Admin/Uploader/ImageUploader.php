<?php

namespace Pingpong\Admin\Uploader;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

class ImageUploader
{
    /**
     * @var string
     */
    protected $ext = '.jpg';

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

        $this->image->save($this->getDestinationFile());

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
}