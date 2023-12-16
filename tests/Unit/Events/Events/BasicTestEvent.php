<?php declare(strict_types=1);

namespace App\Tests\Unit\Framework\Events\Events;

/**
 * Test even for running basic tests.
 */
class BasicTestEvent
{
    /**
     * A number to increment with every iteration
     * of a listener catching this event.
     *
     * @var int
     */
    protected int $number = 0;

    /**
     * Increment the number.
     *
     * @return void
     */
    public function increment(): void
    {
        $this->number++;
    }

    /**
     * Return the number.
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}