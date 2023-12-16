<?php
declare(strict_types=1);

namespace App\src\Framework\Events\Providers;

use App\src\Framework\Events\Providers\ListenerCollection;
use App\src\Framework\Interfaces\Events\ListenerProviderInterface;

/**
 * The Listener Provider provide listeners to the EventDispatcher.
 */
class Provider implements ListenerProviderInterface
{
    /**
     * @var ?ListenerCollection
     */
    protected ?ListenerCollection $listeners = null;

    /**
     * @param \App\src\Framework\Events\Providers\ListenerCollection $listeners
     */
    public function __construct(ListenerCollection $listeners)
    {
        $this->listeners = $listeners;
    }

    /**
     * Return the listeners for the event passed.
     *
     * @param object $event The Event.
     *
     * @return callable
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->listeners->getForEvent(get_class($event));
    }
}