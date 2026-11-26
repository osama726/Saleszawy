<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DocSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocSectionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DocSection');
    }

    public function view(AuthUser $authUser, DocSection $docSection): bool
    {
        return $authUser->can('View:DocSection');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DocSection');
    }

    public function update(AuthUser $authUser, DocSection $docSection): bool
    {
        return $authUser->can('Update:DocSection');
    }

    public function delete(AuthUser $authUser, DocSection $docSection): bool
    {
        return $authUser->can('Delete:DocSection');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DocSection');
    }

    public function restore(AuthUser $authUser, DocSection $docSection): bool
    {
        return $authUser->can('Restore:DocSection');
    }

    public function forceDelete(AuthUser $authUser, DocSection $docSection): bool
    {
        return $authUser->can('ForceDelete:DocSection');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DocSection');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DocSection');
    }

    public function replicate(AuthUser $authUser, DocSection $docSection): bool
    {
        return $authUser->can('Replicate:DocSection');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DocSection');
    }

}