<?php
declare(strict_types=1);

namespace App\Tests\Unit\Framework\Validation\Types;

use App\Framework\Validation\Attributes\ValidationRule;
use App\Framework\Validation\ValidationContext;

class TestDefinitions
{
    #[ValidationRule('foo')]
    public function foo(ValidationContext $context): bool
    {
        return true;
    }

    #[ValidationRule('bar')]
    public function bar(ValidationContext $context): bool
    {
        return true;
    }

    #[ValidationRule('baz')]
    public function baz(ValidationContext $context): bool
    {
        return true;
    }

    #[ValidationRule('qux')]
    public function qux(ValidationContext $context): bool
    {
        return true;
    }
}
