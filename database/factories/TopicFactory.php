<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    $sentence = $faker->sentence();
    // 隨機取得一個月時間
    $updated_at = $faker->dateTimeThisMonth();
    //傳參數生成最大時間不超過，因為創建時間需永遠比更改時間早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'title'      => $sentence,
        'body'       => $faker->text(),
        'excerpt'    => $sentence,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
