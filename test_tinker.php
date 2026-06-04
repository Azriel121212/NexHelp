<?php
$reports = App\Models\Report::with(['reporter', 'reported', 'task'])->latest()->get();
echo "Reports count: " . $reports->count() . "\n";
