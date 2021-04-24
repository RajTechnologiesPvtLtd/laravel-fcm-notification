<?php

namespace RajTechnologies\FCM\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FCM extends Model
{
    use HasFactory;
    
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    

    //variable 
    // @var string|array
    private $to;

    private $registration_ids;
    // @var string
    private $condition;

    private $notification_key;

    private $collapse_key;
    // @var string normal|high
    private $priority = self::PRIORITY_NORMAL;
    // @var bool
    private $content_available;
    //@var bool
    private $mutable_content;
    //@var int
    private $time_to_live;

    private $restricted_package_name;
    //@var bool
    private $dry_run;
    //@var array
    private $PayloadData;
    //@var array
    private $PayloadNotification;

    private $headers = [];

    
    //end variable
    protected $table = 'fcm';
    protected $fillable = [
        'user_id',
        'token'
    ];
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }
    //customs function
    public static function send()
    {
        
    }

    public function formatData(){

    }

    //funcation
    public function priority($priority)
    {
        dd($priority);
        $this->priority = $priority;

        return $this;
    }




}
