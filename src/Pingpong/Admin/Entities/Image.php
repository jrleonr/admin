<?php 

namespace Pingpong\Admin\Entities;

use Pingpong\Presenters\Model;

class Image extends Model 
{

    protected $table = 'images';

    /**
    * @var array
    **/
    protected $fillable = ['title','url','main','user_id', 'post_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(__NAMESPACE__.'\\User');
    }
}