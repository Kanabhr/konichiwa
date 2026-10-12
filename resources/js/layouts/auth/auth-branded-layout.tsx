import { home } from '@/routes';
import { Link } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';

export default function AuthBrandedLayout({
    children,
    title,
    description,
}: PropsWithChildren<{ title: string; description: string }>) {
    return (
        <main className="grid min-h-svh bg-[#faf9f7] lg:grid-cols-[1.05fr_0.95fr]">
            <aside className="relative hidden overflow-hidden bg-[#1c2539] px-12 py-10 text-white lg:flex lg:flex-col xl:px-20">
                <div
                    aria-hidden="true"
                    className="absolute -right-36 -bottom-40 size-[34rem] rounded-full border-[70px] border-white/5"
                />
                <div
                    aria-hidden="true"
                    className="absolute -right-12 -bottom-16 size-[21rem] rounded-full border-[2px] border-[#df0a0a]/50"
                />
                <Link
                    href={home()}
                    className="relative z-10 w-fit rounded bg-white px-3 py-2"
                >
                    <img
                        src="/assets/images/logo/logo10.png"
                        alt="Konichiwa Chemicals"
                        className="h-12 w-auto object-contain"
                    />
                </Link>

                <div className="relative z-10 my-auto max-w-xl py-16">
                    <p className="mb-5 text-sm font-semibold tracking-[0.2em] text-[#ff7777] uppercase">
                        Growing together
                    </p>
                    <h2 className="max-w-lg text-4xl leading-tight font-semibold xl:text-5xl">
                        Solutions for a stronger, more productive tomorrow.
                    </h2>
                    <p className="mt-6 max-w-md text-base leading-7 text-white/70">
                        Welcome to the Konichiwa Chemicals partner portal. Sign
                        in to continue to your account.
                    </p>
                </div>
                <p className="relative z-10 text-sm text-white/50">
                    Konichiwa Chemicals · {new Date().getFullYear()}
                </p>
            </aside>

            <section className="flex min-h-svh items-center justify-center px-6 py-10 sm:px-10">
                <div className="w-full max-w-md">
                    <Link href={home()} className="mb-10 flex w-fit lg:hidden">
                        <img
                            src="/assets/images/logo/logo10.png"
                            alt="Konichiwa Chemicals"
                            className="h-12 w-auto object-contain"
                        />
                    </Link>
                    <div className="rounded-2xl border border-[#e9e5e1] bg-white p-7 shadow-[0_18px_60px_-35px_rgba(28,37,57,0.35)] sm:p-10">
                        <div className="mb-8">
                            <p className="mb-2 text-xs font-semibold tracking-[0.18em] text-[#df0a0a] uppercase">
                                Konichiwa Chemicals
                            </p>
                            <h1 className="text-3xl font-semibold tracking-tight text-[#1c2539]">
                                {title}
                            </h1>
                            <p className="mt-2 text-sm leading-6 text-[#5d666f]">
                                {description}
                            </p>
                        </div>
                        {children}
                    </div>
                    <p className="mt-6 text-center text-xs text-[#7b8188] lg:hidden">
                        Konichiwa Chemicals · {new Date().getFullYear()}
                    </p>
                </div>
            </section>
        </main>
    );
}
