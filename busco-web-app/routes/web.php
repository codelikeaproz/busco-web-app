<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home', ['activePage' => 'home'])->name('home');
Route::view('/about', 'pages.about', ['activePage' => 'about'])->name('about');
Route::view('/services', 'pages.services', ['activePage' => 'services'])->name('services');
Route::view('/process', 'pages.process', ['activePage' => 'process'])->name('process');
Route::view('/news', 'pages.news.index', ['activePage' => 'news'])->name('news.index');
Route::view('/news/article', 'pages.news.show', ['activePage' => 'news'])->name('news.show');
Route::view('/quedan', 'pages.quedan', ['activePage' => 'quedan'])->name('quedan');
Route::view('/community', 'pages.community', ['activePage' => 'community'])->name('community');
Route::view('/careers', 'pages.careers', ['activePage' => 'careers'])->name('careers');
Route::view('/contact', 'pages.contact', ['activePage' => 'contact'])->name('contact');
