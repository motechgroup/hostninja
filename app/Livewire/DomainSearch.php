<?php

namespace App\Livewire;

use App\Models\Domain;
use App\Services\Registrars\RegistrarManager;
use Livewire\Component;

class DomainSearch extends Component
{
    public string $query = '';
    public array $results = [];
    public bool $hasSearched = false;
    public string $cartMessage = '';

    // AI Domain Assistant Properties
    public string $aiPrompt = '';
    public array $aiSuggestions = [];
    public bool $isAiActive = false;
    public bool $hasAiGenerated = false;

    // Filters properties matching left sidebar in Image 3
    public array $selectedTypes = ['popular'];
    public int $maxPrice = 100;

    public array $tldPrices = [
        '.com' => 9.99,
        '.io' => 32.00,
        '.co.ke' => 18.50,
        '.africa' => 12.50,
        '.org' => 14.99,
        '.net' => 12.99,
    ];

    public function mount(RegistrarManager $manager): void
    {
        $this->query = 'hostninja.com';
        $this->search($manager);
    }

    public function toggleAiMode(): void
    {
        $this->isAiActive = !$this->isAiActive;
    }

    public function generateAiSuggestions(RegistrarManager $manager): void
    {
        $this->isAiActive = true;
        $prompt = strtolower(trim($this->aiPrompt));
        if (empty($prompt)) {
            $prompt = 'kenyan tech business cloud';
        }

        // Clean prompt words
        $words = array_filter(explode(' ', preg_replace('/[^a-z0-9\s]/', '', $prompt)));
        $baseKeywords = array_values($words);

        $prefixList = ['get', 'try', 'the', 'go', 'my', 'ninja', 'cloud', 'apex', 'kenya', 'nairobi', 'swift', 'smart', 'hyper'];
        $suffixList = ['hub', 'lab', 'io', 'pay', 'tech', 'app', 'store', 'craft', 'pro', 'express', 'online', 'ke'];

        $generatedNames = [];
        $tlds = ['.com', '.io', '.co.ke', '.africa', '.net'];

        if (count($baseKeywords) >= 2) {
            $generatedNames[] = $baseKeywords[0] . $baseKeywords[1];
            $generatedNames[] = $baseKeywords[1] . $baseKeywords[0];
        }

        foreach ($baseKeywords as $kw) {
            $generatedNames[] = $prefixList[array_rand($prefixList)] . $kw;
            $generatedNames[] = $kw . $suffixList[array_rand($suffixList)];
            $generatedNames[] = 'ninja' . $kw;
        }

        $generatedNames = array_unique($generatedNames);
        $suggestions = [];

        $driver = $manager->driver();

        $reasoningTags = [
            'Short & Memorable',
            'High Conversion TLD',
            'Local Brand Favorite',
            'Tech & Developer Friendly',
            'Pan-African Appeal',
        ];

        foreach (array_slice($generatedNames, 0, 6) as $index => $rawName) {
            $cleanName = preg_replace('/[^a-z0-9]/', '', $rawName);
            if (strlen($cleanName) < 3) continue;

            $tld = $tlds[$index % count($tlds)];
            $fullDomain = $cleanName . $tld;

            $availResult = $driver->checkAvailability($fullDomain);
            $isAvailable = $availResult['available'] ?? true;
            $price = $this->tldPrices[$tld] ?? 12.00;

            $suggestions[] = [
                'name' => $cleanName,
                'tld' => $tld,
                'full_domain' => $fullDomain,
                'available' => $isAvailable,
                'price' => $price,
                'tag' => $reasoningTags[$index % count($reasoningTags)],
            ];
        }

        $this->aiSuggestions = $suggestions;
        $this->hasAiGenerated = true;
    }

    public function selectAiDomain(string $domainName)
    {
        $this->query = $domainName;
        $extParts = explode('.', $domainName);
        $ext = '.' . implode('.', array_slice($extParts, 1));
        $price = $this->tldPrices[$ext] ?? 12.00;
        return $this->addToCart($domainName, $price);
    }

    public function search(RegistrarManager $manager): void
    {
        $input = strtolower(trim($this->query));
        if (empty($input)) {
            $this->results = [];
            $this->hasSearched = false;
            return;
        }

        $cleanInput = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $input);
        $parts = explode('.', $cleanInput);
        $baseName = preg_replace('/[^a-z0-9\-]/', '', $parts[0]);
        if (empty($baseName)) {
            $this->results = [];
            $this->hasSearched = false;
            return;
        }

        $userTld = isset($parts[1]) && !empty($parts[1]) ? '.' . implode('.', array_slice($parts, 1)) : null;

        // Resolve active registrar driver dynamically from RegistrarManager
        $driver = $manager->driver();

        $extensionResults = [];
        $availableCount = 0;

        foreach ($this->tldPrices as $tld => $price) {
            $fullDomain = $baseName . $tld;

            $availResult = $driver->checkAvailability($fullDomain);
            $isAvailable = $availResult['available'] ?? true;

            if ($isAvailable) {
                $availableCount++;
            }

            $extensionResults[] = [
                'domain' => $fullDomain,
                'base_name' => $baseName,
                'extension' => $tld,
                'price' => $price,
                'available' => $isAvailable,
                'is_requested' => ($userTld === $tld || ($userTld === null && $tld === '.com')),
            ];
        }

        $this->results = [
            'base_name' => $baseName,
            'user_typed' => $cleanInput,
            'available_count' => $availableCount,
            'extensions' => $extensionResults,
        ];

        $this->hasSearched = true;
    }

    public function addToCart(string $domainName, float $price)
    {
        $cart = session()->get('cart_domains', []);
        $priceKes = ($price < 100) ? round($price * 130, 2) : $price;
        $cart[$domainName] = $priceKes;
        session()->put('cart_domains', $cart);

        $this->cartMessage = "{$domainName} (KES " . number_format($priceKes, 2) . ") added to registration cart!";

        return redirect()->route('checkout.index', ['domain' => $domainName]);
    }

    public function render()
    {
        return view('livewire.domain-search');
    }
}
