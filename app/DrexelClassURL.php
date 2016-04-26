<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DrexelClassURL extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'classes';

    public function scopeTimestampOfCRN($query, $crn) {
        return $query
            ->where('crn', 'like', $crn)
            ->select('timestamp')
            ;
    }

}
