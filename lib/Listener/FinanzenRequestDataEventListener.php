<?php
namespace OCA\ClubSuiteFinance\Listener;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;

use OCA\ClubSuiteFinance\Events\FinanzenRequestDataEvent;

class FinanzenRequestDataEventListener implements IEventListener {
    public function handle(Event $event): void {
        if (!($event instanceof FinanzenRequestDataEvent)) {
            return;
        }
        $data = ['app' => 'Finanzen', 'balance' => 0];
        $event->respond($data);
    }
}
