import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { useState } from 'react';

interface ContactMessage {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    subject: string | null;
    message: string;
    status: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    message: ContactMessage;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '/admin',
    },
    {
        title: 'Messages',
        href: '/admin/contacts',
    },
    {
        title: 'View',
        href: `/admin/contacts/${props.message.id}`,
    },
];

export default function ContactShow({ message }: Props) {
    const [currentStatus, setCurrentStatus] = useState(message.status);
    const [isUpdating, setIsUpdating] = useState(false);

    const handleStatusChange = async (newStatus: string) => {
        setIsUpdating(true);
        try {
            const response = await fetch(`/admin/contacts/${message.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({ status: newStatus }),
            });

            if (response.ok) {
                setCurrentStatus(newStatus);
            }
        } catch (error) {
            console.error('Error updating status:', error);
        } finally {
            setIsUpdating(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View Message" />
            <div className="py-12">
                <div className="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between mb-8">
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Message Details</h1>
                        <Link
                            href="/admin/contacts"
                            className="text-blue-600 hover:text-blue-800"
                        >
                            ← Back to Messages
                        </Link>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                        <div className="flex items-center justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <div className="flex items-center gap-4">
                                <div className="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-semibold">
                                    {message.name[0].toUpperCase()}
                                </div>
                                <div>
                                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                                        {message.name}
                                    </h2>
                                    <p className="text-sm text-gray-500 dark:text-gray-400">
                                        {new Date(message.created_at).toLocaleString()}
                                    </p>
                                </div>
                            </div>
                            <span className={`px-3 py-1 text-sm rounded-full ${
                                currentStatus === 'unread'
                                    ? 'bg-red-100 text-red-800'
                                    : currentStatus === 'read'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-green-100 text-green-800'
                            }`}>
                                {currentStatus}
                            </span>
                        </div>

                        <div className="space-y-4 mb-8">
                            <div>
                                <label className="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Email
                                </label>
                                <a 
                                    href={`mailto:${message.email}`}
                                    className="text-blue-600 hover:text-blue-800"
                                >
                                    {message.email}
                                </a>
                            </div>

                            {message.phone && (
                                <div>
                                    <label className="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Phone
                                    </label>
                                    <a 
                                        href={`tel:${message.phone}`}
                                        className="text-blue-600 hover:text-blue-800"
                                    >
                                        {message.phone}
                                    </a>
                                </div>
                            )}

                            {message.subject && (
                                <div>
                                    <label className="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Subject
                                    </label>
                                    <p className="text-gray-900 dark:text-white">
                                        {message.subject}
                                    </p>
                                </div>
                            )}

                            <div>
                                <label className="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Message
                                </label>
                                <div className="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                    <p className="text-gray-900 dark:text-white whitespace-pre-wrap">
                                        {message.message}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div className="flex gap-2">
                                <label className="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Status:
                                </label>
                                <select
                                    value={currentStatus}
                                    onChange={(e) => handleStatusChange(e.target.value)}
                                    disabled={isUpdating}
                                    className="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                                >
                                    <option value="unread">Unread</option>
                                    <option value="read">Read</option>
                                    <option value="replied">Replied</option>
                                </select>
                            </div>

                            <a
                                href={`mailto:${message.email}`}
                                className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Reply
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}