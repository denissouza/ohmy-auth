<?php namespace ohmy\http;

/*
 * Copyright (c) 2014, Yahoo! Inc. All rights reserved.
 * Copyrights licensed under the New BSD License.
 * See the accompanying LICENSE file for terms.
 */

interface Rest {

    public function POST($url, Array $arguments, Array $headers);
    public function GET($url, Array $arguments, Array $headers);

} 