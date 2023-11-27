<?php

namespace App\Repository\Facades;

use App\View\Components\Icon as IconView;
use Closure;
use Illuminate\Contracts\View\View;

class Icon
{
    private const SOLID = 'fas';
    private const REGULAR = 'far';
    private const BRAND = 'fab';

    public function new(string $name, string $base = Icon::SOLID): View|string|Closure
    {
        return (new IconView($name, $base))->render();
    }

    public function solid(string $name): View|string|Closure
    {
        return $this->new($name);
    }

    public function regular(string $name): View|string|Closure
    {
        return $this->new($name, Icon::REGULAR);
    }

    public function brand(string $name): View|string|Closure
    {
        return $this->new($name, Icon::BRAND);
    }

    public function signature(): View|string|Closure
    {
        return $this->solid('signature');
    }

    public function supplier(): View|string|Closure
    {
        return $this->signature();
    }

    public function link(): View|string|Closure
    {
        return $this->solid('link');
    }

    public function eraser(): View|string|Closure
    {
        return $this->solid('eraser');
    }

    public function forget(): View|string|Closure
    {
        return $this->eraser();
    }

    public function plus(): View|string|Closure
    {
        return $this->solid('plus');
    }

    public function voucher(): View|string|Closure
    {
        return $this->ticket();
    }

    public function create(): View|string|Closure
    {
        return $this->plus();
    }

    public function note(): View|string|Closure
    {
        return $this->solid('sticky-note');
    }

    public function edit(): View|string|Closure
    {
        return $this->solid('edit');
    }

    public function trash(): View|string|Closure
    {
        return $this->solid('trash-alt');
    }

    public function delete(): View|string|Closure
    {
        return $this->trash();
    }

    public function eye(): View|string|Closure
    {
        return $this->solid('eye');
    }

    public function list(): View|string|Closure
    {
        return $this->solid('list-alt');
    }

    public function up(): View|string|Closure
    {
        return $this->solid('arrow-up');
    }

    public function down(): View|string|Closure
    {
        return $this->solid('arrow-down');
    }

    public function minimize(): View|string|Closure
    {
        return $this->up();
    }

    public function maximize(): View|string|Closure
    {
        return $this->down();
    }

    public function upgrade(): View|string|Closure
    {
        return $this->up();
    }

    public function left(): View|string|Closure
    {
        return $this->solid('arrow-left');
    }

    public function back(): View|string|Closure
    {
        return $this->left();
    }

    public function wand(): View|string|Closure
    {
        return $this->solid('magic');
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
        return $this->solid('home');
    }

    public function accommodation(): View|string|Closure
    {
        return $this->home();
    }

    public function game(): View|string|Closure
    {
        return $this->solid('gamepad');
    }

    public function activity(): View|string|Closure
    {
        return $this->game();
    }

    public function plane(): View|string|Closure
    {
        return $this->solid('plane');
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
        return $this->solid('redo');
    }

    public function fulfil(): View|string|Closure
    {
        return $this->redo();
    }

    public function minus(): View|string|Closure
    {
        return $this->solid('minus-square');
    }

    public function close(): View|string|Closure
    {
        return $this->minus();
    }

    public function chart(): View|string|Closure
    {
        return $this->solid('chart-bar');
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
        return $this->solid('map-signs');
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
        return $this->solid('layer-group');
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
        return $this->solid('copy');
    }

    public function person(): View|string|Closure
    {
        return $this->solid('user');
    }

    public function user(): View|string|Closure
    {
        return $this->person();
    }

    public function customer(): View|string|Closure
    {
        return $this->solid('address-card');
    }

    public function unknownCustomer(): View|string|Closure
    {
        return $this->person();
    }

    public function login(): View|string|Closure
    {
        return $this->solid('sign-in-alt');
    }

    public function logout(): View|string|Closure
    {
        return $this->login();
    }

    public function email(): View|string|Closure
    {
        return $this->solid('envelope');
    }

    public function briefcase(): View|string|Closure
    {
        return $this->solid('briefcase');
    }

    public function merchandise(): View|string|Closure
    {
        return $this->briefcase();
    }

    public function wallet(): View|string|Closure
    {
        return $this->solid('wallet');
    }

    public function phone(): View|string|Closure
    {
        return $this->solid('phone');
    }

    public function globe(): View|string|Closure
    {
        return $this->solid('globe');
    }

    public function tour(): View|string|Closure
    {
        return $this->globe();
    }

    public function website(): View|string|Closure
    {
        return $this->globe();
    }

    public function magnifier(): View|string|Closure
    {
        return $this->solid('search');
    }

    public function view(): View|string|Closure
    {
        return $this->magnifier();
    }

    public function lock(): View|string|Closure
    {
        return $this->solid('lock');
    }

    public function unlock(): View|string|Closure
    {
        return $this->solid('unlock');
    }

    public function key(): View|string|Closure
    {
        return $this->solid('key');
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
        return $this->solid('sync');
    }

    public function approve(): View|string|Closure
    {
        return $this->solid('paper-plane');
    }

    public function convert(): View|string|Closure
    {
        return $this->solid('shopping-bag');
    }

    public function dashboard(): View|string|Closure
    {
        return $this->list();
    }

    public function address(): View|string|Closure
    {
        return $this->solid('map');
    }

    public function order(): View|string|Closure
    {
        return $this->solid('credit-card');
    }

    public function quote(): View|string|Closure
    {
        return $this->wallet();
    }

    public function organization(): View|string|Closure
    {
        return $this->solid('university');
    }

    public function setting(): View|string|Closure
    {
        return $this->solid('wrench');
    }

    public function role(): View|string|Closure
    {
        return $this->solid('sitemap');
    }

    public function logs(): View|string|Closure
    {
        return $this->layers();
    }

    public function report(): View|string|Closure
    {
        return $this->list();
    }

    public function flag(): View|string|Closure
    {
        return $this->solid('flag');
    }

    public function attribute(): View|string|Closure
    {
        return $this->flag();
    }

    public function calendar(): View|string|Closure
    {
        return $this->solid('calendar');
    }

    public function event(): View|string|Closure
    {
        return $this->calendar();
    }

    public function equalizer(): View|string|Closure
    {
        return $this->solid('sliders-h');
    }

    public function options(): View|string|Closure
    {
        return $this->equalizer();
    }

    public function save(): View|string|Closure
    {
        return $this->solid('floppy-disk');
    }

    public function facebook(): View|string|Closure
    {
        return $this->brand('facebook');
    }

    public function twitter(): View|string|Closure
    {
        return $this->brand('twitter');
    }

    public function instagram(): View|string|Closure
    {
        return $this->brand('instagram');
    }

    public function baseball(): View|string|Closure
    {
        return $this->solid('baseball-bat-ball');
    }

    public function rugby(): View|string|Closure
    {
        return $this->solid('football');
    }

    public function basketball(): View|string|Closure
    {
        return $this->solid('basketball');
    }

    public function football(): View|string|Closure
    {
        return $this->solid('futbol');
    }

    public function ticket(): View|string|Closure
    {
        return $this->solid('ticket');
    }

    public function forceDelete(): View|string|Closure
    {
        return $this->solid('triangle-exclamation');
    }

    public function __call(string $name, array $arguments): View|string|Closure
    {
        return $this->solid($name);
    }
}
