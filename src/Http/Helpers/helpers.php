<?php

        use Illuminate\Support\Str;

        /**
         *
         */
        function addSuffixe(string $image, $suffixe = 'mini'): string
        {
            $extension = pathinfo($image)["extension"];
            $fin = "-$suffixe.$extension";
            if (!Str::of($image)->endsWith($fin)) :
                return Str::replace(".$extension", "-$suffixe.$extension", $image);
            else :
                return $image;
            endif;
        }

        function cutterString(string $text, int $limit = 0): string
        {
            $length = $limit ?? Str::length($text);
            return Str::limit($text, $length);
        }
        function camelCase(string $str): string
        {
            return Str::camel($str);
        }