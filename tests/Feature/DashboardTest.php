<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    // The dashboard is admin-only in this project. Create a user with admin role.
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user);

    $this->get(route('admin.dashboard'))->assertOk();
});
