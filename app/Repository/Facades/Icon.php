<?php

namespace App\Repository\Facades;

use App\View\Components\Icon as IconView;
use Closure;
use Illuminate\Contracts\View\View;

class Icon
{
    public function new(string $name): View|string|Closure
    {
        return (new IconView($name))->render();
    }

    public function plus(): View|string|Closure
    {
        return $this->new('plus');
    }

    public function create(): View|string|Closure
    {
        return $this->plus();
    }

    public function note(): View|string|Closure
    {
        return $this->new('note');
    }

    public function edit(): View|string|Closure
    {
        return $this->note();
    }

    public function trash(): View|string|Closure
    {
        return $this->new('trash');
    }

    public function delete(): View|string|Closure
    {
        return $this->trash();
    }

    public function list(): View|string|Closure
    {
        return $this->new('list');
    }

    public function up(): View|string|Closure
    {
        return $this->new('arrow-up');
    }

    public function minimize(): View|string|Closure
    {
        return $this->up();
    }

    public function upgrade(): View|string|Closure
    {
        return $this->up();
    }

    public function left(): View|string|Closure
    {
        return $this->new('arrow-left');
    }

    public function back(): View|string|Closure
    {
        return $this->left();
    }

    public function wand(): View|string|Closure
    {
        return $this->new('magic-wand');
    }

    public function enable(): View|string|Closure
    {
        return $this->wand();
    }

    public function bookable(): View|string|Closure
    {
        return $this->enable();
    }

    public function home(): View|string|Closure
    {
        return $this->new('home');
    }

    public function accommodation(): View|string|Closure
    {
        return $this->home();
    }

    public function game(): View|string|Closure
    {
        return $this->new('game-controller');
    }

    public function activity(): View|string|Closure
    {
        return $this->game();
    }

    public function plane(): View|string|Closure
    {
        return $this->new('plane');
    }

    public function flight(): View|string|Closure
    {
        return $this->plane();
    }

    public function atol(): View|string|Closure
    {
        return $this->plane();
    }

    public function redo(): View|string|Closure
    {
        return $this->new('action-redo');
    }

    public function fulfil(): View|string|Closure
    {
        return $this->redo();
    }

    public function minus(): View|string|Closure
    {
        return $this->new('minus');
    }

    public function close(): View|string|Closure
    {
        return $this->minus();
    }

    public function chart(): View|string|Closure
    {
        return $this->new('chart');
    }

    public function excel(): View|string|Closure
    {
        return $this->chart();
    }

    public function csv(): View|string|Closure
    {
        return $this->list();
    }

    public function directions(): View|string|Closure
    {
        return $this->new('directions');
    }

    public function transport(): View|string|Closure
    {
        return $this->directions();
    }

    public function returnTrip(): View|string|Closure
    {
        return $this->directions();
    }

    public function layers(): View|string|Closure
    {
        return $this->new('layers');
    }

    public function overview(): View|string|Closure
    {
        return $this->layers();
    }

    public function rebuild(): View|string|Closure
    {
        return $this->layers();
    }

    public function copy(): View|string|Closure
    {
        return $this->layers();
    }

    public function person(): View|string|Closure
    {
        return $this->new('user');
    }

    public function user(): View|string|Closure
    {
        return $this->person();
    }

    public function customer(): View|string|Closure
    {
        return $this->person();
    }

    public function unknownCustomer(): View|string|Closure
    {
        return $this->person();
    }

    public function login(): View|string|Closure
    {
        return $this->new('login');
    }

    public function logout(): View|string|Closure
    {
        return $this->login();
    }

    public function email(): View|string|Closure
    {
        return $this->new('envelope');
    }

    public function mustache(): View|string|Closure
    {
        return $this->new('mustache');
    }

    public function briefcase(): View|string|Closure
    {
        return $this->new('briefcase');
    }

    public function merchandise(): View|string|Closure
    {
        return $this->briefcase();
    }

    public function wallet(): View|string|Closure
    {
        return $this->new('wallet');
    }

    public function phone(): View|string|Closure
    {
        return $this->new('call-end');
    }

    public function globe(): View|string|Closure
    {
        return $this->new('globe');
    }

    public function tour(): View|string|Closure
    {
        return $this->globe();
    }

    public function magnifier(): View|string|Closure
    {
        return $this->new('magnifier');
    }

    public function view(): View|string|Closure
    {
        return $this->magnifier();
    }

    public function lock(): View|string|Closure
    {
        return $this->new('lock');
    }

    public function key(): View|string|Closure
    {
        return $this->new('key');
    }

    public function show(): View|string|Closure
    {
        return $this->key();
    }

    public function hide(): View|string|Closure
    {
        return $this->lock();
    }

    public function refresh(): View|string|Closure
    {
        return $this->new('refresh');
    }

    public function approve(): View|string|Closure
    {
        return $this->new('paper-plane');
    }

    public function convert(): View|string|Closure
    {
        return $this->new('bag');
    }
}
