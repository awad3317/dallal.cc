<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\SocialMediaLink;

class SocialMediaLinkRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all SocialMediaLinks.
     * 
     */
    public function index()
    {
        return SocialMediaLink::get();
    }

    /**
     * Retrieve a SocialMediaLink by ID.
     */
    public function getById($id): SocialMediaLink
    {
        return SocialMediaLink::findOrFail($id);
    }

    /**
     * Store a new SocialMediaLink.
     */
    public function store(array $data): SocialMediaLink
    {
        return SocialMediaLink::create($data);
    }

    /**
     * Update an existing SocialMediaLink.
     */
    public function update(array $data, $id): SocialMediaLink
    {
        $SocialMediaLink = SocialMediaLink::findOrFail($id);
        $SocialMediaLink->update($data);
        return $SocialMediaLink;
    }

    /**
     * Delete a SocialMediaLink by ID.
     */
    public function delete($id): bool
    {
        return SocialMediaLink::where('id', $id)->delete() > 0;
    }
    
}
