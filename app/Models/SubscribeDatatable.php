<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeDatatable extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscribe_datatables';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'subscriber',
        'usage',
        'ip'
    ];

    // one-one relationship with IpDatatable
    public function get_ip(){
        return $this->belongsTo(IpDatatable::class, 'ip', 'ip');
    } 
}
