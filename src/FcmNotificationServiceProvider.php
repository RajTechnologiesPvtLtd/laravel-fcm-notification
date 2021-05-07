<?php 

namespace RajTechnologies\FCM;

use GuzzleHttp\Client;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\Notification;
use Illuminate\Support\ServiceProvider;
use RajTechnologies\FCM\Channels\FcmChannel;

class FcmNotificationServiceProvider extends ServiceProvider
{
	public function boot(){
	    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
	}
	public function register(){
		Notification::resolved(function (ChannelManager $service) {
            $service->extend('fcm', function () {
                return new FcmChannel(app(Client::class), config('services.fcm.key'));
            });
        });
	}
}

?>
