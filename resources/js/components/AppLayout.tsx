import { Link, usePage, Form } from '@inertiajs/react';
import AuthController from '@/actions/App/Http/Controllers/AuthController';
import { Button } from '@/components/ui/button';
import type { Auth } from '@/types/auth';

type Props = {
    children: React.ReactNode;
};

export default function AppLayout({ children }: Props) {
    const { auth } = usePage().props as { auth: Auth };
    const user = auth?.user;

    return (
        <div className="min-h-screen bg-background">
            {user && (
                <header className="border-b">
                    <div className="mx-auto flex h-14 max-w-4xl items-center justify-between px-4">
                        <Link href="/todos" className="text-lg font-semibold tracking-tight">
                            Todo App
                        </Link>

                        <div className="flex items-center gap-4">
                            <span className="text-sm text-muted-foreground">{user.email}</span>

                        <Form action={AuthController.logout.url()} method="post" className="m-0 p-0">
                            {({ processing }) => (
                                <Button type="submit" variant="ghost" size="sm" disabled={processing}>
                                    Logout
                                </Button>
                            )}
                        </Form>
                        </div>
                    </div>
                </header>
            )}

            <main className="mx-auto max-w-4xl px-4 py-8">
                {children}
            </main>
        </div>
    );
}
