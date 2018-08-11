<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;

class TimelineController extends Controller
{
    public function show_timeline()
    {
        $public_timeline = Mastodon::domain(env('MASTODON_API'))
            ->get('/timelines/public', ['local' => true]);

        # Embed images.
        foreach ($public_timeline as $index => $status)
        {
            $public_timeline[$index]['content'] = preg_replace(
                '/<a href="([^>]+)\.(png|jpg|jpeg|gif)">([^<]+)<\/a>/',
                '<a href="${1}.${2}"><img src="${1}.${2}" alt="${3}" /></a>',
                $status['content']
            );
        }

        return view('timeline', ['statuses' => $public_timeline]);
    }
}
