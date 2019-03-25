<?php

function textShorten($text, $limit = 400) {
    $text = $text. " ";
    $text = substr($text, 0, $limit);
    $text = substr($text, 0, strrpos($text, ' '));
    $text = $text."...";
    return $text;
}

function getFileExtension($info) {
    $info = new SplFileInfo($info);
    $info->getExtension();
}