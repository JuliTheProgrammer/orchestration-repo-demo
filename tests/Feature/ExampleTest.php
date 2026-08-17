<?php

test('the application redirects to notes', function () {
    $response = $this->get('/');

    $response->assertRedirect('/notes');
});
