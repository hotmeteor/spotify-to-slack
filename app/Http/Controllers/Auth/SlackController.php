<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class SlackController extends Controller
{
    public function handleProviderCallback(Client $client)
    {
        $response = $client->post('https://slack.com/api/oauth.access', [
            'form_params' => [
                'client_id' => config('services.slack.client_id'),
                'client_secret' => config('services.slack.client_secret'),
                'redirect_uri' => config('services.slack.redirect'),
                'code' => request('code'),
            ]
        ]);

        $data = json_decode($response->getBody());

        Auth::user()->update([
            'slack_token' => $data->access_token,
            'slack_user_id' => $data->user_id,
            'slack_webhook_url' => $data->incoming_webhook->url,
        ]);

        return redirect('/');
    }
}
