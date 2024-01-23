<template>
    <q-layout view="hHh lpR fFf">
        <q-header elevated class="text-white bg-primary" height-hint="98">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title class="flex items-center">
                    <q-avatar>
                        <img
                            src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg"
                        />
                    </q-avatar>
                    <q-tabs>
                        <q-route-tab :href="route('dashboard')" label="Title" />
                    </q-tabs>
                </q-toolbar-title>
                <Link :href="route('notification.index')">
                    <q-btn
                        dense
                        color="blue"
                        round
                        icon="notifications"
                        class="q-ml-md"
                    >
                        <q-badge
                            v-if="page.props.notification.notificationCount > 0"
                            color="red"
                            floating
                            >{{ notificationcount }}</q-badge
                        >
                    </q-btn>
                </Link>
                <q-btn-dropdown
                    color="primary"
                    class="ml-4"
                    :label="$page.props.auth.user.name"
                >
                    <q-list>
                        <q-item clickable v-close-popup @click="profile">
                            <q-item-section>
                                <q-item-label>Profile</q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item clickable v-close-popup @click="logout">
                            <q-item-section>
                                <q-item-label>Logout</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-btn-dropdown>
            </q-toolbar>

            <q-tabs align="left">
                <q-route-tab
                    v-if="$page.props.user.roles.includes('admin')"
                    :href="route('permission_ui.permissions.index')"
                    label="Roles"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.includes('employer')"
                    :href="route('report.index')"
                    label="Employee Report"
                />

                <q-route-tab
                    v-if="$page.props.user.roles.includes('employee')"
                    :href="route('report.create')"
                    label="Upload Report"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.includes('employee')"
                    :href="route('report.index')"
                    label="View Reports"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.includes('employer')"
                    to="/page2"
                    label="View Reports"
                />
                <!-- <q-route-tab to="/page2" label="View Reports" /> -->

                <q-route-tab
                    v-if="$page.props.user.roles.includes('manager')"
                    :href="route('report.index')"
                    label="Manager"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.includes('admin')"
                    :href="route('gallery.create')"
                    label="Gallery"
                />
                <q-route-tab
                    v-if="!$page.props.user.roles.includes('admin')"
                    :href="route('gallery.index')"
                    label="Gallery"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.includes('admin')"
                    :href="route('users.create')"
                    label="Approve Users"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.includes('admin')"
                    :href="route('gallery.create')"
                    label="Users"
                />

                <q-route-tab
                    v-if="$page.props.user.roles.length == 0"
                    :href="route('users.create')"
                    label="Apply"
                />
                <q-route-tab to="/page3" label="Contact" />
                <q-route-tab
                    v-if="$page.props.user.roles.length == 0"
                    :href="route('showQr')"
                    label="View QR"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.length == 0"
                    :href="route('scanQr')"
                    label="Scan QR"
                />
                <q-route-tab
                    v-if="$page.props.user.roles.includes('admin')"
                    :href="route('rolesUser')"
                    label="Roles And Users"
                />
            </q-tabs>
        </q-header>

        <q-drawer v-model="leftDrawerOpen" side="left" bordered>
            <q-list bordered padding class="rounded-borders text-primary">
                <q-item
                    clickable
                    v-ripple
                    :active="link === 'inbox'"
                    @click="link = 'inbox'"
                    active-class="my-menu-link"
                >
                    <q-item-section avatar>
                        <q-icon name="inbox" />
                    </q-item-section>

                    <q-item-section>Inbox</q-item-section>
                </q-item>

                <q-item
                    clickable
                    v-ripple
                    :active="link === 'outbox'"
                    @click="link = 'outbox'"
                    active-class="my-menu-link"
                >
                    <q-item-section avatar>
                        <q-icon name="send" />
                    </q-item-section>

                    <q-item-section>Outbox</q-item-section>
                </q-item>

                <q-item
                    clickable
                    v-ripple
                    :active="link === 'trash'"
                    @click="link = 'trash'"
                    active-class="my-menu-link"
                >
                    <q-item-section avatar>
                        <q-icon name="delete" />
                    </q-item-section>

                    <q-item-section>Trash</q-item-section>
                </q-item>

                <q-separator spaced />

                <q-item
                    clickable
                    v-ripple
                    :active="link === 'settings'"
                    @click="link = 'settings'"
                    active-class="my-menu-link"
                >
                    <q-item-section avatar>
                        <q-icon name="settings" />
                    </q-item-section>

                    <q-item-section>Settings</q-item-section>
                </q-item>

                <q-item
                    clickable
                    v-ripple
                    :active="link === 'help'"
                    @click="link = 'help'"
                    active-class="my-menu-link"
                >
                    <q-item-section avatar>
                        <q-icon name="help" />
                    </q-item-section>

                    <q-item-section>Help</q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container>
            <slot></slot>
        </q-page-container>

        <q-footer elevated class="text-white bg-grey-8">
            <q-toolbar align="middle">
                <q-toolbar-title>
                    <q-avatar>
                        <img
                            src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg"
                        />
                    </q-avatar>
                    <div align="middle">Title</div>
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>
    </q-layout>
</template>

<script setup>
import { computed, ref } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";

const page = usePage();

const link = ref("");

const leftDrawerOpen = ref(false);
const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value;
};

// Redirect to a route using Inertia
const profile = () => {
    // Use Inertia to visit the profile route
    router.get(route("profile.show"));
    // router.visit("/user/profile-information", { method: "get" });
};
const logout = () => {
    // Use Inertia to visit the logout route
    router.post(route("logout"));
};

const notificationcount = computed(() =>
    Math.min(page.props.notification.notificationCount, 9)
);
</script>
