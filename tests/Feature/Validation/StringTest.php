<?php
//declare(strict_types=1);

namespace App\Tests\Feature\Framework\Validation;

use App\Framework\Validation\Validator;

dataset('other_types_then_string', [
    null,
    true,
    1,
    2.0,
    [],
    new \stdClass(),
    function () {
    },
    // Resource how ?
]);

test('value \'test\' should be considered a string', function () {

    $validator = new Validator([
        'field' => 'test',
    ]);

    $validator->validate([
        'field' => 'string'
    ]);

    $errors = $validator->errors();
    expect(count($errors))->toEqual(0);
});

test('Other types then strings should fail', function (mixed $type = null) {
    $validator = new Validator([
        'field' => $type,
    ]);

    $validator->validate([
        'field' => 'string'
    ]);

    expect($validator->passes())->toBeFalsy()
        ->and($validator->fails())->toBeTruthy();

    $errors = $validator->errors();
    expect($errors['field'])->toEqual("Field field is not of type string.")
        ->and(count($errors))->toEqual(1);
})->with('other_types_then_string');
