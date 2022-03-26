# Laravel FCM Notification
Laravel FCM (Firebase Cloud Messaging) Notification Channel

Use this package to send push notifications via Laravel to Firebase Cloud Messaging. Laravel 5.5+ required.

This package works only with [Legacy HTTP Server Protocol](https://firebase.google.com/docs/cloud-messaging/http-server-ref)

## Install

This package can be installed through Composer.

``` bash
composer require rajtechnologies/laravel-fcm-notification
```

If installing on < Laravel 5.5 then add the service provider:

```php
// config/app.php
'providers' => [
    ...
    RajTechnologies\FCM\FcmNotificationServiceProvider::class,
    ...
];
```

Add your Firebase API Key in `config/services.php`.

```php
return [
   
    ...
    ...
    /*
     * Add the Firebase API key
     */
    'fcm' => [
        'key' => env('FCM_SECRET_KEY')
     ]
];
```

Model of FCM

```php
use RajTechnologies\FCM\Models\FCM;
```
Change in User Model
```php
    public function fcm()
    {
        return $this->hasMany(FCM::class);
    }
```
## Example Usage

Use Artisan to create a notification:

```bash
php artisan make:notification SomeNotification
```

Return `[fcm]` in the `public function via($notifiable)` method of your notification:

```php
public function via($notifiable)
{
    return ['fcm'];
}
```

Add the method `public function toFcm($notifiable)` to your notification, and return an instance of `FcmMessage`: 

```php
use RajTechnologies\FCM\FcmMessage;

...

public function toFcm($notifiable) 
{
    $message = new FcmMessage();
    $message->content([
        'title'        => 'Foo', 
        'body'         => 'Bar', 
        'sound'        => '', // Optional 
        'icon'         => '', // Optional
        'click_action' => '' // Optional
    ])->data([
        'param1' => 'baz' // Optional
    ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
    
    return $message;
}
```
# OR Add User Model Functions

When sending to specific device using Modal, make sure your notifiable entity has `routeNotificationForFcm` method defined: 

Add Method For Notification Function
```php
public function routeNotificationForFcm($notification)
    {
        //For Single Token Only
        /*
        if($this->fcm){
            return $this->fcm[0]->token;
        }
        */
        if($this->fcm){
            $array = $this->fcm->pluck('token')->toArray();
        return implode(',',$array);
        }
        return null;
    }
```

Add Static Method For Update Token

```php
public static function updateFCM($user_id,$fcm_token){
        $fcmQuery = FCM::query();
        $fcm = $fcmQuery->where('user_id',$user_id)->first();
        if($fcm){
            $fcm->update([
                "token" =>$fcm_token
            ]);
            return true;
        }
        $fcmQuery->create([
            "user_id" =>$user_id,
            "token" =>$fcm_token
        ]);
        return true;
    }
``` 

When sending to a topic, you may define so within the `toFcm` method in the notification:

```php
use RajTechnologies\FCM\FcmMessage;

...

public function toFcm($notifiable) 
{
    $message = new FcmMessage();
    $message->to('the-topic', $recipientIsTopic = true)
    ->content([...])
    ->data([...]);
    
    return $message;
}
```

Or when sending with a condition:

```php
use RajTechnologies\FCM\FcmMessage;

...

public function toFcm($notifiable) 
{
    $message = new FcmMessage();
    $message->contentAvailable(true)
        ->priority('high')
        ->condition("'user_".$notifiable->id."' in topics")
        ->data([...]);
    
    return $message;
}
```

You may provide optional headers or override the request headers using `setHeaders()`:

```php
use RajTechnologies\FCM\FcmMessage;

...

public function toFcm($notifiable) 
{
    $message = new FcmMessage();
    $message->setHeaders([
        'project_id'    =>  "123456789"   // FCM sender_id
    ])->content([
        'title'        => 'Foo', 
        'body'         => 'Bar', 
        'sound'        => '', // Optional 
        'icon'         => '', // Optional
        'click_action' => '' // Optional
    ])->data([
        'param1' => 'baz' // Optional
    ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
    
    return $message;
}
```

## Interpreting a Response

To process any laravel notification channel response check [Laravel Notification Events](https://laravel.com/docs/6.0/notifications#notification-events)

This channel return a json array response: 
```json
 {
    "multicast_id": "number",
    "success": "number",
    "failure": "number",
    "canonical_ids": "number",
    "results": "array"
 }
```

Check [FCM Legacy HTTP Server Protocol](https://firebase.google.com/docs/cloud-messaging/http-server-ref#interpret-downstream) 
for response interpreting documentation.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## First Inspiration of Admin Panel

- **[Laravel FCM (Firebase Cloud Messaging) Notification Channel (Ben Wilkins)](https://github.com/benwilkins/laravel-fcm-notification)**