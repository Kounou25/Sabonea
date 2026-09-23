<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.accueil')->name('accueil');
Route::view('/a-propos', 'pages.a-propos')->name('a-propos');
Route::view('/comment-ca-fonctionne', 'pages.comment-ca-fonctionne')->name('comment-ca-fonctionne');
Route::view('/secteurs', 'pages.secteurs')->name('secteurs');
Route::view('/pourquoi-sabonea', 'pages.pourquoi-sabonea')->name('pourquoi-sabonea');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/expression-de-besoin', 'pages.expression-de-besoin')->name('expression-de-besoin');
Route::view('/fournisseur-exemple', 'pages.fournisseur-exemple')->name('fournisseur-exemple');
