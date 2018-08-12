<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Mastodon;

class TimelineController extends Controller
{
    public function public_timeline()
    {
        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->get('/timelines/public', ['local' => true]);

        $timeline = $this->embed_images($timeline);

        return view('timeline', ['statuses' => $timeline]);
    }

    public function home_timeline()
    {
        $user = session('user');
        $timeline = Mastodon::domain(env('MASTODON_DOMAIN'))
            ->token($user->token)
            ->get('/timelines/home');

        $timeline = $this->embed_images($timeline);

        return view('timeline', ['statuses' => $timeline]);
    }

    private function embed_images($timeline)
    {
        foreach ($timeline as $index => $status)
        {
            # Search for links to images and replace the inner HTML
            # with an img tag of the image itself.
            $timeline[$index]['content'] = preg_replace(
                '/<a href="([^>]+)\.(png|jpg|jpeg|gif)">([^<]+)<\/a>/',
                '<a href="${1}.${2}"><img src="${1}.${2}" alt="${3}" /></a>',
                $status['content']
            );
        }

        return $timeline;
    }
}
