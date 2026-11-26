<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DocArticle;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocArticlePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DocArticle');
    }

    public function view(AuthUser $authUser, DocArticle $docArticle): bool
    {
        return $authUser->can('View:DocArticle');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DocArticle');
    }

    public function update(AuthUser $authUser, DocArticle $docArticle): bool
    {
        return $authUser->can('Update:DocArticle');
    }

    public function delete(AuthUser $authUser, DocArticle $docArticle): bool
    {
        return $authUser->can('Delete:DocArticle');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DocArticle');
    }

    public function restore(AuthUser $authUser, DocArticle $docArticle): bool
    {
        return $authUser->can('Restore:DocArticle');
    }

    public function forceDelete(AuthUser $authUser, DocArticle $docArticle): bool
    {
        return $authUser->can('ForceDelete:DocArticle');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DocArticle');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DocArticle');
    }

    public function replicate(AuthUser $authUser, DocArticle $docArticle): bool
    {
        return $authUser->can('Replicate:DocArticle');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DocArticle');
    }

}