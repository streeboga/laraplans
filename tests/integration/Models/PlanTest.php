<?php

namespace Gerarodjbaez\LaraPlans\Tests\Integration\Models;

use Gerardojbaez\LaraPlans\Tests\TestCase;
use Gerardojbaez\LaraPlans\Models\Plan;
use Gerardojbaez\LaraPlans\Models\PlanFeature;

class PlanTest extends TestCase
{
    /**
     * Can create a plan with features attached.
     *
     * @test
     * @return void
     */
    public function it_can_create_a_plan_and_attach_features_to_it()
    {
        $plan = Plan::create([
            'name' => 'Pro',
            'description' => 'Pro plan',
            'code' => 'pro',
            'price' => 9.99,
            'interval' => 'month',
            'interval_count' => 1,
            'trial_period_days' => 15,
            'sort_order' => 1,
        ]);

        $plan->features()->saveMany([
            new PlanFeature(['name' => '50 Listings', 'code' => 'listings_per_month', 'value' => 50, 'sort_order' => 1]),
            new PlanFeature(['name' => '10 Pictures per listing', 'code' => 'pictures_per_listing', 'value' => 10, 'sort_order' => 5]),
            new PlanFeature(['name' => '30 Days Duration', 'code' => 'listing_duration_days', 'value' => 30, 'sort_order' => 10]),
        ]);

        $plan = Plan::with('features')->byCode('pro')->first();

        $this->assertEquals('Pro', $plan->name);
        $this->assertEquals(3, $plan->features->count());
    }

    /**
     * Check if plan is free or not.
     *
     * @test
     * @return void
     */
    public function it_can_check_if_plan_is_free_or_not()
    {
        $free = new Plan([
            'price' => 0.00
        ]);

        $notFree = new Plan([
            'price' => 9.99
        ]);

        $this->assertTrue($free->isFree());
        $this->assertFalse($notFree->isFree());
    }

    /**
     * Check if plan is has trial.
     *
     * @test
     * @return void
     */
    public function it_has_trial()
    {
        $withoutTrial = new Plan([
            'trial_period_days' => 0
        ]);

        $withTrial = new Plan([
            'trial_period_days' => 5
        ]);

        $this->assertTrue($withTrial->hasTrial());
        $this->assertFalse($withoutTrial->hasTrial());
    }
}