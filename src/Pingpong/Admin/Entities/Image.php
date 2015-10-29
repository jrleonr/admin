<?php 

namespace Pingpong\Admin\Entities;

use Pingpong\Presenters\Model;

class Image extends Model 
{

    protected $table = 'images';

    /**
    * @var array
    **/
    protected $fillable = ['name','url','main','user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(__NAMESPACE__.'\\User');
    }
}