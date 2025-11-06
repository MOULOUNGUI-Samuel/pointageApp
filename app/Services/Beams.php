<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Beams
{
    private string $instanceId;
    private string $secret;

    public function __construct(?string $instanceId = null, ?string $secret = null)
    {
        $this->instanceId = $instanceId ?? (string) config('services.beams.instance_id', env('BEAMS_INSTANCE_ID'));
        $this->secret     = $secret     ?? (string) config('services.beams.secret_key',  env('BEAMS_SECRET_KEY'));
    }

    /** Publie vers des interests Beams */
    public function publishToInterests(array $interests, array $notification, ?string $deepLink = null): bool
    {
        if (!$this->instanceId || !$this->secret) {
            Log::error('Beams config missing', ['instance' => $this->instanceId, 'secret_set' => (bool) $this->secret]);
            return false;
        }

        $url = sprintf(
            'https://%s.pushnotifications.pusher.com/publish_api/v1/instances/%s/publishes',
            $this->instanceId,
            $this->instanceId
        );

        $payload = [
            'interests' => array_values(array_unique($interests)),
            'web' => [
                'notification' => $notification,   // ex: ['title'=>'','body'=>'',...]
            ],
        ];
        if ($deepLink) {
            $payload['web']['deep_link'] = $deepLink;
        }

        $res = Http::withToken($this->secret)->asJson()->post($url, $payload);

        if ($res->successful()) return true;

        Log::error('Beams publish failed', ['status' => $res->status(), 'body' => $res->body()]);
        return false;
    }

    /** (optionnel) Publie vers des users authentifiés */
    public function publishToUsers(array $userIds, array $notification, ?string $deepLink = null): bool
    {
        if (!$this->instanceId || !$this->secret) return false;

        $url = sprintf(
            'https://%s.pushnotifications.pusher.com/publish_api/v1/instances/%s/publishes/users',
            $this->instanceId,
            $this->instanceId
        );

        $payload = [
            'users' => array_values(array_unique($userIds)),
            'web' => [
                'notification' => $notification,
            ],
        ];
        if ($deepLink) $payload['web']['deep_link'] = $deepLink;

        $res = Http::withToken($this->secret)->asJson()->post($url, $payload);

        return $res->successful();
    }
}