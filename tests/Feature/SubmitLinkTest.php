<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitLinkTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testExample()
//    {
//        $this->assertTrue(true);
//    }

    /** @test */
    function guest_can_submit_a_new_link()
    {
    }

    /** @test */
    function link_is_not_created_if_validation_fails()
    {
    }

    /** @test */
    function link_is_not_created_with_an_invalid_url()
    {
    }

    /** @test */
    function  max_length_fails_when_too_long()
    {

    }

    /** @test */
    function max_length_succeeds_when_under_max()
    {
    }
}
