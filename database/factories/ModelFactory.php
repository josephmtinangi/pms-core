<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ClientType::class, function (Faker\Generator $faker) {

    return [
        'name' => ucfirst($faker->word),
        'description' => $faker->sentence,
    ];
});

$factory->define(App\Models\Region::class, function (Faker\Generator $faker) {

	$name = $faker->city;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'state' => 'tz',
    ];
});

$factory->define(App\Models\District::class, function (Faker\Generator $faker) {

	$name = $faker->city;
    return [
        'name' => $name,
        'region_id' => function () {
        	return factory('App\Models\Region')->create()->id;
        },
    ];
});

$factory->define(App\Models\Ward::class, function (Faker\Generator $faker) {

	$name = $faker->city;
    return [
        'name' => $name,
        'district_id' => function () {
        	return factory('App\Models\District')->create()->id;
        },
    ];
});

$factory->define(App\Models\Village::class, function (Faker\Generator $faker) {

	$name = $faker->city;
    return [
        'name' => $name,
        'ward_id' => function () {
        	return factory('App\Models\Ward')->create()->id;
        },
    ];
});

$factory->define(App\Models\PaymentMode::class, function (Faker\Generator $faker) {

    return [
        'name' => ucfirst($faker->word),
        'code' => sprintf('%01d', $faker->unique()->randomDigit),
        'description' => $faker->sentence,
    ];
});

$factory->define(App\Models\Client::class, function (Faker\Generator $faker) {

	$phone = $faker->e164PhoneNumber;
	
    return [
        'code' => sprintf('%03d', $faker->unique()->randomDigit),
        'first_name' => $faker->firstName,
        'middle_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'client_type_id' => function () {
        	return factory('App\Models\ClientType')->create()->id;
        },
        'phone' => $phone,
        'email' => $faker->companyEmail,
        'password' => bcrypt($phone),
        'avatar' => $faker->imageUrl(150, 150),
        'login_count' => $faker->randomDigit,
        'physical_address' => $faker->state,
        'postal_address' => $faker->postcode,
        'tin' => $faker->isbn10,
    ];
});

$factory->define(App\Models\PropertyType::class, function (Faker\Generator $faker) {

    return [
        'name' => ucfirst($faker->word),
        'description' => $faker->sentence,
    ];
});

$factory->define(App\Models\Property::class, function (Faker\Generator $faker) {

    return [
    	'name' => $faker->catchPhrase,
        'property_type_id' => function () {
        	return factory('App\Models\PropertyType')->create()->id;
        },
        'payment_mode_id' => function () {
        	return factory('App\Models\PaymentMode')->create()->id;
        },
        'client_id' => function () {
        	return factory('App\Models\Client')->create()->id;
        },
        'physical_address' => $faker->city,
        'floors' => $faker->randomDigit,
        'village_id' => function () {
        	return factory('App\Models\Village')->create()->id;
        },
    ];
});

$factory->define(App\Models\ClientProperty::class, function (Faker\Generator $faker) {

    return [
        'client_id' => function () {
        	return factory('App\Models\Client')->create()->id;
        },
        'property_id' => function () {
        	return factory('App\Models\Property')->create()->id;
        },
    ];
});
