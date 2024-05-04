<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class B2B extends Model
{
    use HasFactory;

    const STATUS_PENDING = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_PROCESSING = 3;
    const STATUS_COMMUNICATED = 4;
    const STATUS_CANCELLED = 5;

    protected $table = 'b2b';

    protected $fillable = [
        'nid_number',
        'name',
        'nid_upload',
        'doc_upload',
        'business_name',
        'business_location',
        'type_of_business',
        'tin_number',
        'bin_number',
        'contact_number',
        'email_address',
        'status'
    ];

}
