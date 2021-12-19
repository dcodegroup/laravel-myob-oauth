<?php

namespace Dcodegroup\LaravelMyobOauth;

use Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\Provider\Provider;
use Illuminate\Support\Facades\Schema;
use League\OAuth2\Client\Token\AccessToken;

class MyobTokenService
{
    /**
     * @return null|\League\OAuth2\Client\Token\AccessToken|mixed
     *@throws \Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob
     */
    public static function getToken()
    {
        if (! Schema::hasTable((new MyobToken())->getTable())) {
            return null;
        }

        $token = MyobToken::latestToken();

        if (! $token) {
            return null;
        }

        $oauth2Token = $token->toOAuth2Token();

        if ($oauth2Token->hasExpired()) {
            $oauth2Token = self::getAccessTokenFromMyob($oauth2Token);

            if (! MyobToken::isValidTokenFormat($oauth2Token)) {
                throw new UnauthorizedMyob('Token is invalid or the provided token has invalid format!');
            }

            MyobToken::create($oauth2Token->jsonSerialize());
        }

        return $oauth2Token;
    }

    /**
     * @return mixed
     */
    private static function getAccessTokenFromMyob(AccessToken $token)
    {
        return resolve(Provider::class)->getAccessToken('refresh_token', [
            'refresh_token' => $token->getRefreshToken(),
        ]);
    }
}
