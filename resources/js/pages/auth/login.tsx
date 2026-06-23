import { Head, Link, Form } from '@inertiajs/react';
import AuthController from '@/actions/App/Http/Controllers/AuthController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function Login() {
    return (
        <>
            <Head title="Login" />

            <div className="flex min-h-screen items-center justify-center px-4">
                <Card className="w-full max-w-sm">
                    <CardHeader className="text-center">
                        <CardTitle className="text-xl">Login</CardTitle>
                        <CardDescription>Masuk ke akun Anda</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form
                            action={AuthController.login.url()}
                            method="post"
                            className="space-y-4"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="email">Email</Label>
                                        <Input
                                            id="email"
                                            name="email"
                                            type="email"
                                            required
                                            placeholder="user@example.com"
                                        />
                                        <InputError message={errors.email} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="password">Password</Label>
                                        <Input
                                            id="password"
                                            name="password"
                                            type="password"
                                            placeholder="Kosongkan atau isi bebas"
                                        />
                                        <p className="text-xs text-muted-foreground">
                                            Password: <code>password</code> (development)
                                        </p>
                                    </div>

                                    <Button type="submit" className="w-full" disabled={processing}>
                                        Login
                                    </Button>

                                    <p className="text-center text-sm text-muted-foreground">
                                        Belum punya akun?{' '}
                                        <Link href="/register" className="text-primary underline-offset-4 hover:underline">
                                            Daftar
                                        </Link>
                                    </p>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
