<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //修改策略自動發現邏輯
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            //動態返回模型對應的策略名稱如：// 'App\Model\User' => 'App\Policies\UserPolicy',
            return 'App\Policies\\' . class_basename($modelClass) . 'Policy';
        });
    }
}
