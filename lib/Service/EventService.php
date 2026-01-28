<?php
namespace OCA\ClubSuiteFinance\Service;

use OCP\EventDispatcher\IEventDispatcher;
use OCA\ClubSuiteFinance\Events\FinanzenBasicEvent;
use OCA\ClubSuiteFinance\Events\FinanzenCallbackEvent;
use OCA\ClubSuiteFinance\Events\FinanzenRequestDataEvent;

class EventService {
    private IEventDispatcher $dispatcher;

    public function __construct(IEventDispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public function dispatchBasicEvent(array $payload): void {
        $event = new FinanzenBasicEvent(uniqid('fin_', true), time(), $payload);
        $this->dispatcher->dispatch($event);
    }

    public function dispatchCallbackEvent(array $payload, callable $callback): void {
        $event = new FinanzenCallbackEvent(uniqid('fin_cb_', true), time(), $payload, $callback);
        $this->dispatcher->dispatch($event);
    }

    public function dispatchRequestDataEvent(callable $callback): void {
        $event = new FinanzenRequestDataEvent(uniqid('fin_req_', true), time(), [], $callback);
        $this->dispatcher->dispatch($event);
    }
}
