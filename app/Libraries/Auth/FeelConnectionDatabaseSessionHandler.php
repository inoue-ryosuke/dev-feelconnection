<?php

namespace App\Libraries\Auth;

use Illuminate\Support\Arr;
use SessionHandlerInterface;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\QueryException;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Container\Container;

class FeelConnectionDatabaseSessionHandler extends DatabaseSessionHandler
{
    /**
     * Get the default payload for the session.
     *
     * @param  string  $data
     * @return array
     */
    protected function getDefaultPayload($data)
    {
//        $user_id = (auth('customer')->check()) ? auth('customer')->user()->getAuthIdentifier() : null;
        logger('FeelConnectionDatabaseSessionHandler getDefaultPayload start');
        // ガードに対応するプライマリーキーを取得
        if (auth('customer')->check()) {
            $user_id = auth('customer')->user()->getAuthIdentifier();
        } else if (auth('user_master')->check()) {
            $user_id = auth('user_master')->user()->getAuthIdentifier();
        } else {
            $user_id = null;
        }

        logger('user_id');
        logger($user_id);
        $payload = [
            'payload' => base64_encode($data),
            'last_activity' => $this->currentTime(),
            'user_id' => $user_id,
        ];
        logger('getDefaultPayload');
        logger($payload);
        if (! $this->container) {
            return $payload;
        }

        return tap($payload, function (&$payload) {
//            $this->addUserInformation($payload)
            $this->addRequestInformation($payload);
        });
    }
    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        logger('write FeelConnectionDatabaseSessionHandler');
        logger($data);
        $payload = $this->getDefaultPayload($data);
        logger($payload);
        if (! $this->exists) {
            $this->read($sessionId);
        }
        logger('database session exists');
        if ($this->exists) {
            $this->performUpdate($sessionId, $payload);
        } else {
            $this->performInsert($sessionId, $payload);
        }

        return $this->exists = true;
    }


}
