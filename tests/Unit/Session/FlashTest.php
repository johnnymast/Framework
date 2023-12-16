<?php

namespace App\Tests\Unit\Framework\Session;

use App\Framework\Session\Session;

beforeEach(function () {
    $session = new Session(['testing' => true]);
    $session->clear();

    $this->flash = $session->getFlash();
});

test(
    'Values added should be retrieved with get.',
    function () {
        $value = 'I will be set an its awesome and it will be flashy';
        $key = 'myawesomeflashkey';

        $this->flash->add($key, $value);

        $expected = ['I will be set an its awesome and it will be flashy'];

        $actual = $this->flash->get($key);

        expect($actual)->toEqual($expected);
    }
);

test(
    'Values set should be retrieved with get.',
    function () {
        $value = 'I will be set an its awesome and it will be flashy';
        $key = 'myawesomeflashkey';

        $this->flash->add($key, $value);

        $actcual = $this->flash->get($key);
        $expected = ['I will be set an its awesome and it will be flashy'];

        expect($actcual)->toEqual($expected);
    }
);

test(
    'If a key is set has should return true.',
    function () {
        $value = 'somevalue';
        $key = 'somekeyforhas';

        $this->flash->add($key, $value);

        $actual = $this->flash->has($key);

        expect($actual)->toBeTrue();
    }
);

test(
    'If a key is read it will be deleted.',
    function () {
        $value = 'somevalue';
        $key = 'someflashkeyforhas';

        $this->flash->add($key, $value);

        $actual = $this->flash->has($key);

        expect($actual)->toBeTrue();

        $this->flash->get($key);

        $actual = $this->flash->has($key);

        expect($actual)->toBeFalse();
    }
);

test(
    'clear should remove all existing flash messages',
    function () {
        $values = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        foreach ($values as $key => $value) {
            $this->flash->add($key, $value);
        }

        $expected = [
            'key1' => ['value1'],
            'key2' => ['value2'],
        ];

        $actual = $this->flash->all();

        expect($actual)->toEqual($expected);

        $this->flash->clear();

        $expected = [];

        $actual = $this->flash->all();

        expect($actual)->toEqual($expected);
    }
);

test(
    'After getting a value it should be automatically deleted',
    function () {
        $this->flash->add('success', 'story');

        $actual = $this->flash->has('success');

        expect($actual)->toBeTrue();

        $this->flash->get('success');

        $actual = $this->flash->has('success');

        expect($actual)->toBeFalse();
    }
);


test(
    'Keys that are not make has return false.',
    function () {
        $actual = $this->flash->has('noexisting');

        expect($actual)->toBeFalse();
    }
);



test(
    'Set should add a array.',
    function () {
       $data = [
         'field' => 'value',
         'field2' => 'value2',
       ];

       $this->flash->set('success', $data);

       $expected = $data;
       $actual = $this->flash->get('success');

       expect($actual)->toEqual($expected);
    }
);
