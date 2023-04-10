<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Arr;

class CleanController extends Controller
{
    public function clean_video()
    {
        $courses = Course::query()->get();
        $files = scandir(public_path('/files/files'));
        $filter = Arr::where($files, function ($value, $key) {
            $val = explode('.', $value);
            if ($val[1]=='mp4') return $value;
        });
         $del = [];
        foreach ($filter as $f) {
            $boo = true;
            foreach ($courses as $c) {
                if ($c->video == 'files/'.$f) $boo = false;
            }
            if($boo) array_push($del, $f);
        }

        foreach ($del as $d) {
            unlink(public_path('/files/files/'.$d));
        }

        $files = scandir(public_path('/files/files'));
        $filter2 = Arr::where($files, function ($value, $key) {
            $val = explode('.', $value);
            if ($val[1]=='mp4') return $value;
        });

        dd($filter2);
    }

    public function clean_pdf()
    {
        $courses = Course::query()->get();
        $files = scandir(public_path('/files/files'));
        $filter = Arr::where($files, function ($value, $key) {
            $val = explode('.', $value);
            if ($val[1]=='pdf') return $value;
        });
        $del = [];
        foreach ($filter as $f) {
            $boo = true;
            foreach ($courses as $c) {
                if ($c->file == 'files/'.$f) $boo = false;
            }
            if($boo) array_push($del, $f);
        }

        foreach ($del as $d) {
            unlink(public_path('/files/files/'.$d));
        }

        $files = scandir(public_path('/files/files'));
        $filter2 = Arr::where($files, function ($value, $key) {
            $val = explode('.', $value);
            if ($val[1]=='pdf') return $value;
        });

        dd($filter2);
    }

    public function clean_course_img()
    {
        $courses = Course::query()->get();
        $files = scandir(public_path('/files'));
        $filter = Arr::where($files, function ($value, $key) {
            $val = explode('_', $value);
            if ($val[0]=='course') return $value;
        });
        $del = [];
        foreach ($filter as $f) {
            $boo = true;
            foreach ($courses as $c) {
                if ($c->image == $f) $boo = false;
            }
            if($boo) array_push($del, $f);
        }

        foreach ($del as $d) {
            unlink(public_path('/files/'.$d));
        }

        $files = scandir(public_path('/files'));
        $filter2 = Arr::where($files, function ($value, $key) {
            $val = explode('_', $value);
            if ($val[0]=='course') return $value;
        });

        dd($filter2);
    }
}
