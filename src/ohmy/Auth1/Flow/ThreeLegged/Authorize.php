<?php namespace ohmy\Auth1\Flow\ThreeLegged;

/*
 * Copyright (c) 2014, Yahoo! Inc. All rights reserved.
 * Copyrights licensed under the New BSD License.
 * See the accompanying LICENSE file for terms.
 */

use ohmy\Auth\Promise,
    ohmy\Http\Rest,
    ohmy\Auth1\Security\Signature;

class Authorize extends Promise {

    public function __construct($callback, Rest $client=null) {
        parent::__construct($callback);
        $this->client = $client;
    }

    public function access($url, $options=array()) {
        $promise = $this;
        return (new Access(function($resolve, $reject) use($promise, $url, $options) {

            $signature = new Signature(
                'POST',
                $url,
                array_intersect_key(
                    $promise->value,
                    array_flip(array(
                        'oauth_consumer_key',
                        'oauth_consumer_secret',
                        'oauth_nonce',
                        'oauth_signature_method',
                        'oauth_timestamp',
                        'oauth_token',
                        'oauth_token_secret',
                        'oauth_verifier',
                        'oauth_version'
                    ))
                )
            );

            $promise->client->POST($url, array(), array(
                'Authorization'  => $signature,
                'Content-Length' => 0
            ))
            ->then(function($response) use($resolve) {
                $resolve($response->text());
            });

        }, $this->client))

        ->then(function($data) use($promise) {
            parse_str($data, $array);
            return array_merge($promise->value, $array);
        });
    }
}
