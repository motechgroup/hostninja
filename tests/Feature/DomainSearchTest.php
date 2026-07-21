<?php

namespace Tests\Feature;

use App\Livewire\DomainSearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DomainSearchTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_domain_search_returns_results(): void
    {
        Livewire::test(DomainSearch::class)
            ->set('query', 'mycoolnewbrand.com')
            ->call('search')
            ->assertSet('hasSearched', true)
            ->assertSee('mycoolnewbrand.com');
    }
}
