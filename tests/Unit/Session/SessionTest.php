<?php

namespace App\Tests\Unit\Framework\Session;

use App\Framework\Session\Session;

beforeEach(function () {
    $this->session = new Session(['testing' => true]);
});

test(
    'Values set should be retrieved with get.',
    function () {
        $expected = 'I will be set an its awesome';
        $key = 'myawesomekey';
        $this->session->set($key, $expected);

        $actcual = $this->session->get($key);

        expect($actcual)->toEqual($expected);
    }
);

test(
    'If a key is set has should return true.',
    function () {
        $value = 'somevalue';
        $key = 'somekeyforhas';

        $this->session->set($key, $value);

        $actcual = $this->session->has($key);

        expect($actcual)->toBeTrue();
    }
);

test(
    'If a key is deleted has should return false.',
    function () {
        $value = 'somevalue';
        $key = 'somekeyforhas';

        $this->session->set($key, $value);

        $actcual = $this->session->has($key);

        expect($actcual)->toBeTrue();

        $this->session->delete($key);

        $actcual = $this->session->has($key);

        expect($actcual)->toBeFalse();
    }
);

test(
    'clear should empty the session',
    function () {
        $value = 'somevalue';
        $key = 'somekeyforclear';

        $this->session->set($key, $value);

        $actcual = $this->session->has($key);

        expect($actcual)->toBeTrue();

        $this->session->clear();

        $actcual = $this->session->has($key);

        expect($actcual)->toBeFalse();
    }
);

test(
    'setValues should initialize as session with preset values.',
    function () {
        $expected = [
            'somerandomkey' => 'somevalue',
            'anotherkey' => 'anothervalue',
        ];

        $this->session->setValues($expected);

        $actual = $this->session->all();

        expect($actual)->toEqual($expected);
    }
);

test(
    'getId should return the session id.',
    function () {
        $expected = session_id();
        $actual = $this->session->getId();

        expect($actual)->toEqual($expected);
    }
);

test(
    'getInstance should return the same instance as the session.',
    function () {
        $expected = $this->session;
        $actual = $this->session->getInstance();

        expect($actual)->toEqual($expected);
    }
);

test(
    'Set should add a array.',
    function () {
        $data = [
            'field' => 'value',
            'field2' => 'value2',
        ];

        $this->session->set('success', $data);

        $expected = $data;
        $actual = $this->session->get('success');

        expect($actual)->toEqual($expected);
    }
);