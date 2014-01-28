<?php namespace ohmy;

/*
 * Copyright (c) 2014, Yahoo! Inc. All rights reserved.
 * Copyrights licensed under the New BSD License.
 * See the accompanying LICENSE file for terms.
 */

use ohmy\Auth\Model,
    ohmy\Auth1\TwoLegged,
    ohmy\Auth1\ThreeLegged;

class Auth1 {

    public static function init($type, $params=array()) {
        $model = new Model(array(
            'oauth_consumer_key'       => $params['consumer_key'],
            'oauth_consumer_secret'    => $params['consumer_secret'],
            'oauth_token'              => $_REQUEST['oauth_token'],
            'oauth_token_secret'       => $_SESSION['oauth_token_secret'],
            'oauth_verifier'           => $_REQUEST['oauth_verifier'],
            'oauth_callback'           => ($params['callback']) ? $params['callback'] : '',
            'oauth_callback_confirmed' => $_SESSION['oauth_callback_confirmed']
        ));

        switch($type) {
            case 2:
                return new TwoLegged($model, function($resolve) {
                    $resolve(true);
                });
                break;
            case 3:
                return new ThreeLegged($model, function($resolve) {
                    $resolve(true);
                });
                break;
            default:
        }
    }

    public static function i($type) {
        $oauth = array(
            'oauth_consumer_key'       => '',
            'oauth_consumer_secret'    => '',
            'oauth_callback'           => '',
            'oauth_token'              => $_REQUEST['oauth_token'],
            'oauth_token_secret'       => $_SESSION['oauth_token_secret'],
            'oauth_verifier'           => $_REQUEST['oauth_verifier'],
            'oauth_callback_confirmed' => $_SESSION['oauth_callback_confirmed'],
            'oauth_nonce'              => md5(mt_rand()),
            'oauth_timestamp'          => time(),
            'oauth_signature_method'   => 'HMAC-SHA1',
            'oauth_version'            => '1.0'
        );
        return new TwoLegged(function($resolve, $reject) use($oauth) {
            $resolve($oauth);
        });
    }

}