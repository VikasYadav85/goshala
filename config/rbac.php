<?php

use App\Models\User;

/*
|--------------------------------------------------------------------------
| RBAC catalog
|--------------------------------------------------------------------------
| Canonical permissions and the default role → permission presets, consumed
| by RolePermissionSeeder (to seed) and the admin nav/route gating (to check).
| `key` is the Spatie permission name used in `can()` / `permission:` middleware;
| `group` is display-only (permission matrix grouping). Adding a section here and
| gating its route with `permission:<key>` is all that's needed to wire it up.
*/

return [

    // The panel-entry permission (not tied to a single section).
    'access' => User::PERMISSION_ACCESS_ADMIN,

    // Seeded catalog: key => [group, label].
    'permissions' => [
        User::PERMISSION_ACCESS_ADMIN => ['group' => 'Access', 'label' => 'Access admin panel'],

        'manage-donations' => ['group' => 'Donations', 'label' => 'Manage donations'],
        'manage-donation-categories' => ['group' => 'Donations', 'label' => 'Manage donation categories'],
        'manage-campaigns' => ['group' => 'Donations', 'label' => 'Manage campaigns'],

        'manage-cows' => ['group' => 'Goshala', 'label' => 'Manage cows'],
        'manage-events' => ['group' => 'Goshala', 'label' => 'Manage events'],
        'manage-gallery' => ['group' => 'Goshala', 'label' => 'Manage gallery'],

        'manage-blog' => ['group' => 'Content', 'label' => 'Manage blog'],
        'manage-testimonials' => ['group' => 'Content', 'label' => 'Manage testimonials'],
        'manage-team' => ['group' => 'Content', 'label' => 'Manage team'],
        'manage-faqs' => ['group' => 'Content', 'label' => 'Manage FAQs'],

        'manage-volunteers' => ['group' => 'Engagement', 'label' => 'Manage volunteers'],
        'manage-messages' => ['group' => 'Engagement', 'label' => 'Manage messages'],

        'manage-settings' => ['group' => 'Settings', 'label' => 'Manage site settings'],

        'manage-users' => ['group' => 'Access control', 'label' => 'Manage users'],
        'manage-roles' => ['group' => 'Access control', 'label' => 'Manage roles'],
        'manage-permissions' => ['group' => 'Access control', 'label' => 'Manage permissions'],
    ],

    /*
    | Default role presets. super_admin is intentionally omitted: it is granted
    | every permission (current and future) at seed time and bypasses all gates.
    | Access-control permissions (users/roles/permissions) are super_admin only.
    */
    'roles' => [
        User::ROLE_ADMIN => [
            'access-admin',
            'manage-donations', 'manage-donation-categories', 'manage-campaigns',
            'manage-cows', 'manage-events', 'manage-gallery',
            'manage-blog', 'manage-testimonials', 'manage-team', 'manage-faqs',
            'manage-volunteers', 'manage-messages',
            'manage-settings',
        ],
        User::ROLE_EDITOR => [
            'access-admin',
            'manage-campaigns',
            'manage-cows', 'manage-events', 'manage-gallery',
            'manage-blog', 'manage-testimonials', 'manage-team', 'manage-faqs',
        ],
        User::ROLE_STAFF => [
            // No access-admin → cannot enter the panel.
        ],
    ],
];
