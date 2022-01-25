<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpDatatable extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ip_datatables';

    /**
     * The primary key for the model.
     * last 3 digits of full ip address
     *
     * @var string
     */
    protected $primaryKey = 'ip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'gateway',
        'current_subscriber_id'
    ];

    // for showing ip address
    public $prefix = "140.116.82.";

    // one-one relationship with SubscribeDatatable
    public function get_subscribe(){
        return $this->hasOne(SubscribeDatatable::class, 'id', 'current_subscriber_id');
    } 
}