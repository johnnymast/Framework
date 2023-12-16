<?php
declare(strict_types=1);

namespace App\Tests\Feature\Framework\Validation;

use App\Framework\Validation\Validator;

beforeEach(function () {
    $validator = new Validator([
        'field' => false,
    ]);
});

dataset('other_types_then_boolean', [
    null,
    "string",
    1,
    2.0,
    [],
    new \stdClass(),
    function () {
    },
    // Resource how ?
]);


test(
    'value false should be considered a boolean',
    function () {

        $validator = new Validator([
        'field' => false,
        ]);

        $validator->validate([
        'field' => 'boolean'
        ]);

        $errors = $validator->errors();
        expect(count($errors))->toEqual(0);
    }
);

test(
    '[ALIAS] value false should be considered a bool',
    function () {

        $validator = new Validator([
        'field' => false,
        ]);

        $validator->validate([
        'field' => 'bool'
        ]);

        $errors = $validator->errors();
        expect(count($errors))->toEqual(0);
    }
);

test(
    'value true should be considered a boolean',
    function () {

        $validator = new Validator([
        'field' => false,
        ]);

        $validator->validate([
        'field' => 'boolean'
        ]);

        $errors = $validator->errors();
        expect(count($errors))->toEqual(0);
    }
);

test(
    'running the boolean type check on a non-existing key in the target array should fail.',
    function () {

        $validator = new Validator([
        'field' => false,
        ]);

        $validator->validate([
        'nonexisting' => 'boolean'
        ]);

        expect($validator->fails())->toBeTruthy()
        ->and($validator->passes())->toBeFalsy();


        $errors = $validator->errors();
        expect(count($errors))->toEqual(1);
    }
);

test('Other types then integers should fail', function (mixed $type = null) {
    $validator = new Validator([
        'field' => $type,
    ]);

    $validator->validate([
        'field' => 'boolean'
    ]);

    expect($validator->passes())->toBeFalsy()
        ->and($validator->fails())->toBeTruthy();


    $errors = $validator->errors();
    expect($errors['field'])->toEqual("Field field is not of type boolean.")
        ->and(count($errors))->toEqual(1);
})->with('other_types_then_boolean');
