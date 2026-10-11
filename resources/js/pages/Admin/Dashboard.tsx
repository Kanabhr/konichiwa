import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';

interface Stats {
    total_blogs: number;
    published_blogs: number;
    draft_blogs: number;
    total_views: number;
    unread_messages: number;
    total_messages: number;
}

interface Blog {
    id: number;
    title: string;
    slug: string;
    status: string;
    created_at: string;
}

interface ContactMessage {
    id: number;
    name: string;
    email: string;
    subject: string | null;
    status: string;
    created_at: string;
}

interface Props {
    stats: Stats;
    recent_blogs: Blog[];
    recent_messages: ContactMessage[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '/admin',
    },
    {
        title: 'Dashboard',
        href: '/admin',
    },
];

export default function AdminDashboard({ stats, recent_blogs, recent_messages }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Admin Dashboard" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-8">Admin Dashboard</h1>

                    {/* Stats Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-500 dark:text-gray-400">Total Blogs</p>
                                    <p className="text-3xl font-bold text-gray-900 dark:text-white">{stats.total_blogs}</p>
                                </div>
                                <div className="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                            </div>
                            <div className="mt-4 flex gap-4 text-sm">
                                <span className="text-green-600">{stats.published_blogs} published</span>
                                <span className="text-yellow-600">{stats.draft_blogs} drafts</span>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-500 dark:text-gray-400">Total Views</p>
                                    <p className="text-3xl font-bold text-gray-900 dark:text-white">{stats.total_views}</p>
                                </div>
                                <div className="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                    <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-500 dark:text-gray-400">Contact Messages</p>
                                    <p className="text-3xl font-bold text-gray-900 dark:text-white">{stats.total_messages}</p>
                                </div>
                                <div className="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div className="mt-4 text-sm">
                                <span className="text-orange-600">{stats.unread_messages} unread</span>
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {/* Recent Blogs */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="flex items-center justify-between mb-4">
                                <h2 className="text-xl font-semibold text-gray-900 dark:text-white">Recent Blogs</h2>
                                <a href="/admin/blogs" className="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                            </div>
                            <div className="space-y-4">
                                {recent_blogs.length > 0 ? (
                                    recent_blogs.map((blog) => (
                                        <div key={blog.id} className="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                            <div>
                                                <a href={`/admin/blogs/${blog.id}/edit`} className="font-medium text-gray-900 dark:text-white hover:text-blue-600">
                                                    {blog.title}
                                                </a>
                                                <p className="text-sm text-gray-500 dark:text-gray-400">
                                                    {new Date(blog.created_at).toLocaleDateString()}
                                                </p>
                                            </div>
                                            <span className={`px-2 py-1 text-xs rounded-full ${
                                                blog.status === 'published' 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-yellow-100 text-yellow-800'
                                            }`}>
                                                {blog.status}
                                            </span>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-gray-500 dark:text-gray-400">No blogs yet.</p>
                                )}
                            </div>
                        </div>

                        {/* Recent Messages */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="flex items-center justify-between mb-4">
                                <h2 className="text-xl font-semibold text-gray-900 dark:text-white">Recent Messages</h2>
                                <a href="/admin/contacts" className="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                            </div>
                            <div className="space-y-4">
                                {recent_messages.length > 0 ? (
                                    recent_messages.map((message) => (
                                        <div key={message.id} className="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                            <div>
                                                <a href={`/admin/contacts/${message.id}`} className="font-medium text-gray-900 dark:text-white hover:text-blue-600">
                                                    {message.name}
                                                </a>
                                                <p className="text-sm text-gray-500 dark:text-gray-400">
                                                    {message.subject || message.email}
                                                </p>
                                            </div>
                                            <span className={`px-2 py-1 text-xs rounded-full ${
                                                message.status === 'unread' 
                                                    ? 'bg-red-100 text-red-800' 
                                                    : message.status === 'read'
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : 'bg-green-100 text-green-800'
                                            }`}>
                                                {message.status}
                                            </span>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-gray-500 dark:text-gray-400">No messages yet.</p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Quick Actions */}
                    <div className="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="/admin/blogs/create" className="flex items-center justify-center gap-2 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                            </svg>
                            Create New Blog
                        </a>
                        <a href="/admin/contacts" className="flex items-center justify-center gap-2 bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            View Messages
                        </a>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}