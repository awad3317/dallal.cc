<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Contact;

class ContactRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Contacts with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Contact::paginate(10);
    }

    /**
     * Retrieve a Contact by ID.
     */
    public function getById($id): Contact
    {
        return Contact::findOrFail($id);
    }

    /**
     * Store a new Contact.
     */
    public function store(array $data): Contact
    {
        return Contact::create($data);
    }

    /**
     * Update an existing Contact.
     */
    public function update(array $data, $id): Contact
    {
        $Contact = Contact::findOrFail($id);
        $Contact->update($data);
        return $Contact;
    }

    /**
     * Delete a Contact by ID.
     */
    public function delete($id): bool
    {
        return Contact::where('id', $id)->delete() > 0;
    }
    
}
