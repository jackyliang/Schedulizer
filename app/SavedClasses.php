<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedClasses extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saved_classes';

    /**
     * PK used by the model
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Fillable columns
     * @var array
     */
    protected $fillable = array('session');

    /**
     * Get hash of id accessor
     *
     * @return string
     */
    public function getHashAttribute()
    {
        return \Hashids::encode($this->attributes['id']);
    }

}
