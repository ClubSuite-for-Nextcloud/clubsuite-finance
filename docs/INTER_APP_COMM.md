Inter-App Communication (Finanzen)

Erläuterung der Event-Typen und Beispielcode.

Sender-Beispiel:
```
$eventService->dispatchCallbackEvent(['tx'=>123], function($res){ /* ... */ });
```

Listener-Beispiel:
```
public function handle(CallbackEvent $e) { $e->triggerCallback(['handled'=>true]); }
```
