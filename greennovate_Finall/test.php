<?php
try {
    $m = App\Models\Message::first();
    if ($m) {
        echo "Found message ID: {$m->id}\n";
        $m->delete();
        echo "Deleted successfully.\n";
    } else {
        echo "No messages in database.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
