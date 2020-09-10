<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Part;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(Part::class, function (Faker $faker) {
    $part = UploadedFile::fake()->create('example-cad-file.stl', 1000);
    $savedPath = Storage::disk('local')->putFile('cad_files',$part);
    return [
        'url' => $savedPath
    ];
});
