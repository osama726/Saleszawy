<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FeatureCategory;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FeatureCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FeatureCategory');
    }

    public function view(AuthUser $authUser, FeatureCategory $featureCategory): bool
    {
        return $authUser->can('View:FeatureCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FeatureCategory');
    }

    public function update(AuthUser $authUser, FeatureCategory $featureCategory): bool
    {
        return $authUser->can('Update:FeatureCategory');
    }

    public function delete(AuthUser $authUser, FeatureCategory $featureCategory): bool
    {
        return $authUser->can('Delete:FeatureCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FeatureCategory');
    }

    public function restore(AuthUser $authUser, FeatureCategory $featureCategory): bool
    {
        return $authUser->can('Restore:FeatureCategory');
    }

    public function forceDelete(AuthUser $authUser, FeatureCategory $featureCategory): bool
    {
        return $authUser->can('ForceDelete:FeatureCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FeatureCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FeatureCategory');
    }

    public function replicate(AuthUser $authUser, FeatureCategory $featureCategory): bool
    {
        return $authUser->can('Replicate:FeatureCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FeatureCategory');
    }
}
