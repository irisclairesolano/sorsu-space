<?php

use App\Services\AiJsonParser;

it('parses valid json responses', function () {
    $payload = ['title' => 'Test', 'summary' => 'Ok'];
    $json = json_encode($payload);

    expect(AiJsonParser::parse($json))->toBe($payload);
});

it('repairs and parses wrapped json content', function () {
    $content = "Here is your json: {\"title\":\"Notes\",\"summary\":\"Done\"}";
    $parsed = AiJsonParser::parse($content);

    expect($parsed['title'])->toBe('Notes');
});
