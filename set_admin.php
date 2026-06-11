<?php
$user = App\Models\User::first();
if($user) {
    $user->is_admin = true;
    $user->save();
    echo "Admin set for " . $user->name . "!\n";
} else {
    echo "No users found!\n";
}
