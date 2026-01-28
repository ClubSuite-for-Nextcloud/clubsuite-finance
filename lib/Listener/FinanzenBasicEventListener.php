<?php
namespace OCA\ClubSuiteFinance\Listener;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;

use OCA\ClubSuiteFinance\Events\FinanzenBasicEvent;

class FinanzenBasicEventListener implements IEventListener {
    public function handle(Event $event): void {
        if (!($event instanceof FinanzenBasicEvent)) {
            return;
        }
        error_log('FinanzenBasicEvent received in Finanzen: ' . $event->getId());
    }
}
