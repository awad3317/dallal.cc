<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\SiteSetting;

class SiteSettingRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all SiteSettings.
     * 
     */
    public function index()
    {
        return SiteSetting::get();
    }

    /**
     * Retrieve a SiteSetting by ID.
     */
    public function getById($id): SiteSetting
    {
        return SiteSetting::findOrFail($id);
    }

    /**
     * Store a new SiteSetting.
     */
    public function store(array $data): SiteSetting
    {
        return SiteSetting::create($data);
    }

    /**
     * Update an existing SiteSetting.
     */
    public function update(array $data, $id): SiteSetting
    {
        $SiteSetting = SiteSetting::firstOrUpdate([], $data);
        return $SiteSetting;
    }

    /**
     * Delete a SiteSetting by ID.
     */
    public function delete($id): bool
    {
        return SiteSetting::where('id', $id)->delete() > 0;
    }
    
}
