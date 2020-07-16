<?php

namespace App\Http\Controllers;

use GPBMetadata\Google\Api\Log;
use Illuminate\Http\Request;
use Google\Cloud\PubSub\PubSubClient;

class PubsubController extends Controller
{
    public function setJob() {


        $pubSub = new PubSubClient([
            'keyFilePath' => base_path('serviceAccounts').DIRECTORY_SEPARATOR.'serverless-playground-283219-9b7a97b700d1.json'
        ]);

// Get an instance of a previously created topic.
        $topic = $pubSub->topic('test_topic');

// Publish a message to the topic.
        $topic->publish([
            'data' => 'My new message.',
            'attributes' => [
                'location' => 'Detroit'
            ]
        ]);

    }

    public function receiver() {
        dump(request()->all());
        \Log::info(request()->all());
    }

    public function workJob() {
        $pubSub = new PubSubClient([
            'keyFilePath' => base_path('serviceAccounts').DIRECTORY_SEPARATOR.'serverless-playground-283219-9b7a97b700d1.json'
        ]);

// Get an instance of a previously created subscription.
        $subscription = $pubSub->subscription('test_subscription');

// Pull all available messages.
        $messages = $subscription->pull();

        foreach ($messages as $message) {
            echo $message->data() . "\n";
            echo $message->attribute('location');
            $subscription->acknowledge($message);

        }
    }
}
