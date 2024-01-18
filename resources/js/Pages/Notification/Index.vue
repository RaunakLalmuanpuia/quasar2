<template>
    <QuasarLayout>
        <div
            v-if="$page.props.flash.message"
            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert"
        >
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>

        <h1 class="mb-4 text-3xl">Your Notifications</h1>

        <section
            v-if="notifications.data.length"
            class="text-gray-700 dark:text-gray-400"
        >
            <div
                class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-800"
                v-for="notification in notifications.data"
                :key="notification.id"
            >
                <div v-if="$page.props.user.roles.includes('employer')">
                    <div v-if="notification.data.manager_status === 'rejected'">
                        <span
                            v-if="
                                notification.type ===
                                'App\\Notifications\\ReportVerified'
                            "
                            >Report was Rejected by Manager
                            {{ notification.data.manager_name }} with File Name
                            <Link
                                :href="
                                    route(
                                        'report.employee.show',
                                        notification.data.report_id
                                    )
                                "
                                class="text-indigo-600"
                            >
                                {{ notification.data.filename }}
                            </Link>
                            <br />
                            Manger Status :
                            {{ notification.data.manager_status }}
                            <br />
                            Manager Feedback:
                            {{ notification.data.manager_feedback }}
                        </span>
                    </div>
                    <div v-if="notification.data.manager_status === 'accepted'">
                        <span
                            v-if="
                                notification.type ===
                                'App\\Notifications\\ReportVerified'
                            "
                            >Report was Approved by Manager Name:
                            {{ notification.data.manager_name }} with File Name:
                            <Link
                                :href="
                                    route(
                                        'report.employer.show',
                                        notification.data.report_id
                                    )
                                "
                                class="text-indigo-600"
                            >
                                {{ notification.data.filename }}
                            </Link>
                            <br />
                            Employer Name :
                            {{ notification.data.employee_name }}
                            <br />
                            Manager Status :
                            {{ notification.data.manager_status }}
                            <br />
                            Manager Feedback:
                            {{ notification.data.manager_feedback }}
                        </span>
                    </div>
                    <div v-else>
                        <span
                            v-if="
                                notification.type ===
                                'App\\Notifications\\ReportVerified'
                            "
                            >Report was uploaded by
                            {{ notification.data.employee_name }} with Name
                            <Link
                                :href="
                                    route(
                                        'report.employee.show',
                                        notification.data.report_id
                                    )
                                "
                                class="text-indigo-600"
                            >
                                {{ notification.data.filename }}
                            </Link>
                        </span>
                    </div>
                </div>

                <div v-if="$page.props.user.roles.includes('employee')">
                    <div
                        v-if="notification.data.employer_status === 'rejected'"
                    >
                        <span
                            v-if="
                                notification.type ===
                                'App\\Notifications\\ReportVerified'
                            "
                            >Report was Rejected by
                            {{ notification.data.employer_name }} with File Name
                            <Link
                                :href="
                                    route(
                                        'report.employee.show',
                                        notification.data.report_id
                                    )
                                "
                                class="text-indigo-600"
                            >
                                {{ notification.data.filename }}
                            </Link>
                            <br />
                            Employer Status :
                            {{ notification.data.employer_status }}
                            <br />
                            Employer Feedback:
                            {{ notification.data.employer_feedback }}
                        </span>
                    </div>
                    <div
                        v-if="
                            notification.data.employer_status === 'accepted' &&
                            notification.data.manager_status === 'pending'
                        "
                    >
                        <span
                            v-if="
                                notification.type ===
                                'App\\Notifications\\ReportVerified'
                            "
                            >Report was Approved by
                            {{ notification.data.employer_name }} with File Name
                            <Link
                                :href="
                                    route(
                                        'report.employee.show',
                                        notification.data.report_id
                                    )
                                "
                                class="text-indigo-600"
                            >
                                {{ notification.data.filename }}
                            </Link>
                            <br />
                            Employer Status :
                            {{ notification.data.employer_status }}
                            <br />
                            Employer Feedback:
                            {{ notification.data.employer_feedback }}
                            <br />
                            Manager Forwarded to:
                            {{ notification.data.manager_name }}
                        </span>
                    </div>
                    <div
                        v-if="
                            notification.data.manager_status === 'accepted' &&
                            notification.data.employer_status === 'accepted'
                        "
                    >
                        <span
                            v-if="
                                notification.type ===
                                'App\\Notifications\\ReportVerified'
                            "
                            >Report was Further Approved by Manager:
                            {{ notification.data.manager_name }} with File Name
                            <Link
                                :href="
                                    route(
                                        'report.employee.show',
                                        notification.data.report_id
                                    )
                                "
                                class="text-indigo-600"
                            >
                                {{ notification.data.filename }}
                            </Link>
                            <br />
                            Employer Name :
                            {{ notification.data.employer_name }}
                            <br />
                            Employer Status :
                            {{ notification.data.employer_status }}
                            <br />
                            Employer Feedback:
                            {{ notification.data.employer_feedback }}
                            <br />
                            Manager Name :
                            {{ notification.data.manager_name }}
                            Manager Feedback :
                            {{ notification.data.manager_feedback }}
                        </span>
                    </div>
                </div>

                <div v-if="$page.props.user.roles.includes('manager')">
                    <span
                        v-if="
                            notification.type ===
                            'App\\Notifications\\ReportVerified'
                        "
                        >Report Submitted by Employee:
                        {{ notification.data.employee_name }} with File Name:
                        <Link
                            :href="
                                route(
                                    'report.show',
                                    notification.data.report_id
                                )
                            "
                            class="text-indigo-600"
                        >
                            {{ notification.data.filename }}
                        </Link>
                        <br />
                        Was Approved by Employer
                        {{ notification.data.employer_name }}
                        <br />
                        Employer Feedback:
                        {{ notification.data.employer_feedback }}
                    </span>
                </div>
                <div>
                    <Link
                        :href="route('notification.seen', notification.id)"
                        as="button"
                        method="put"
                        v-if="!notification.read_at"
                        >Mark as read</Link
                    >
                </div>
            </div>
        </section>
        <section v-else class="text-gray-700 dark:text-gray-400">
            No Notificaton Yet
        </section>

        <section
            v-if="notifications.data.length"
            class="flex justify-center w-full mt-8 mb-8"
        >
            <div class="flex justify-center mt-4">
                <div class="flex gap-1">
                    <Link
                        v-for="(link, index) in notifications.links"
                        :key="index"
                        class="px-4 py-2 rounded-md"
                        :href="link.url || ''"
                        :class="{
                            'bg-indigo-500 dark:bg-indigo-800 text-gray-300':
                                link.active,
                        }"
                        v-html="link.label"
                    />
                </div>
            </div>
        </section>
    </QuasarLayout>
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    notifications: Object,
});
// const props = defineProps({
//     employerPendingFiles: Object,
//     manager: Object,
// });
</script>
