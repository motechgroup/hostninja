<?php

namespace App\Services\Registrars\Contracts;

interface RegistrarInterface
{
    /**
     * Check single domain availability.
     */
    public function checkAvailability(string $domain): array;

    /**
     * Bulk search availability across multiple domains or TLDs.
     */
    public function bulkSearch(array $domains): array;

    /**
     * Get domain name suggestions based on a keyword.
     */
    public function getSuggestions(string $keyword): array;

    /**
     * Register a new domain name.
     */
    public function registerDomain(array $params): array;

    /**
     * Renew an existing domain name.
     */
    public function renewDomain(array $params): array;

    /**
     * Initiate or check domain transfer.
     */
    public function transferDomain(array $params): array;

    /**
     * Get status of domain transfer request.
     */
    public function getTransferStatus(string $domain): array;

    /**
     * Fetch complete domain metadata from registry.
     */
    public function getDomainInfo(string $domain): array;

    /**
     * Check if domain lock is enabled.
     */
    public function getDomainLock(string $domain): bool;

    /**
     * Enable or disable domain lock.
     */
    public function setDomainLock(string $domain, bool $lock): bool;

    /**
     * Enable or disable WHOIS privacy protection.
     */
    public function setWhoisPrivacy(string $domain, bool $enable): bool;

    /**
     * Update domain registrant/technical WHOIS contact details.
     */
    public function updateContactDetails(string $domain, array $contacts): bool;

    /**
     * Get current nameservers assigned to domain.
     */
    public function getNameservers(string $domain): array;

    /**
     * Update nameservers for domain.
     */
    public function updateNameservers(string $domain, array $nameservers): bool;

    /**
     * Get DNS zone records.
     */
    public function getDnsRecords(string $domain): array;

    /**
     * Update or add DNS zone records.
     */
    public function updateDnsRecords(string $domain, array $records): bool;

    /**
     * Get DNSSEC Delegation Signer (DS) records.
     */
    public function getDnssec(string $domain): array;

    /**
     * Update DNSSEC records.
     */
    public function updateDnssec(string $domain, array $dsRecords): bool;

    /**
     * Get Child Nameservers / Glue Records.
     */
    public function getGlueRecords(string $domain): array;

    /**
     * Update Child Nameservers / Glue Records.
     */
    public function updateGlueRecords(string $domain, array $glueRecords): bool;

    /**
     * Toggle auto renew setting.
     */
    public function setAutoRenew(string $domain, bool $autoRenew): bool;

    /**
     * Check premium domain pricing if applicable.
     */
    public function getPremiumPrice(string $domain): ?float;

    /**
     * Synchronize registry status, expiry date, WHOIS and nameservers.
     */
    public function syncDomainStatus(string $domain): array;

    /**
     * Test API connection with configured credentials.
     */
    public function testConnection(): bool;
}
