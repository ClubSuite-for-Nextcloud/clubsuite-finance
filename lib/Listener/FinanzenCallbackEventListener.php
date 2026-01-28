<?php
namespace OCA\ClubSuiteFinance\Listener;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;

use OCA\ClubSuiteFinance\Events\FinanzenCallbackEvent;

class FinanzenCallbackEventListener implements IEventListener {
    public function handle(Event $event): void {
        if (!($event instanceof FinanzenCallbackEvent)) {
            return;
        }
        $payload = $event->getPayload();
        $event->triggerCallback(['handledBy' => 'Finanzen', 'count' => count($payload)]);
    }
}
