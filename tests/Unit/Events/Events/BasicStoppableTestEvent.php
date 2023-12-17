<?php declare(strict_types=1);

namespace App\Framework\tests\Unit\Events\Events;

use App\Framework\Interfaces\Events\StoppableEventInterface;

/**
 * Test even for running propagation tests.
 */
class BasicStoppableTestEvent extends BasicTestEvent implements StoppableEventInterface
{
    /**
     * Value indicating if propagation is still
     * active.
     *
     * @var bool
     */
    protected bool $isPropagating = true;

    /**
     * Change the propagation value.
     *
     * @param bool $value The value to set.
     *
     * @return void
     */
    public function setPropagationIsStopped(bool $value): void
    {
        $this->isPropagating = !$value;
    }

    /**
     * Indicate if the propagation is stopped.
     *
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return ($this->isPropagating == false);
    }
}