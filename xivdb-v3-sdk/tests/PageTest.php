<?php

use PHPUnit\Framework\TestCase;
use XIV\XivService;

final class PageTest extends TestCase
{
    public function testPageCanBeCreated(): void
    {
        // titles need to be unique
        $title = 'Hello World #'. time();

        $response = (new XivService())->Page->create([
            'title' => $title,
            'html' => '<h1>Hello</h1>'
        ]);

        $this->assertEquals($title, $response->title);
    }
}
