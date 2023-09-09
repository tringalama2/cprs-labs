<?php

it('restricts dashboard to admin users', function () {
    login(null, ['is_admin' => false])
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});
