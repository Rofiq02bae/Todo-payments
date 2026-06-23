import { Head, Link, Form } from '@inertiajs/react';
import AuthController from '@/actions/App/Http/Controllers/AuthController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function Register() {
    return (
        <>
            <Head title="Register" />

            <div className="flex min-h-screen items-center justify-center px-4">
                <Card className="w-full max-w-sm">
                    <CardHeader className="text-center">
                        <CardTitle className="text-xl">Daftar Akun Baru</CardTitle>
                        <CardDescription>Buat akun untuk mengelola Todo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form
                            action={AuthController.register.url()}
                            method="post"
                            className="space-y-4"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="name">Nama</Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            required
                                            placeholder="Nama Anda"
                                        />
                                        <InputError message={errors.name} />
                                    </div>

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

                                    <div className="text-xs text-muted-foreground">
                                        Password akan diisi otomatis (development mode).
                                    </div>

                                    <Button type="submit" className="w-full" disabled={processing}>
                                        Daftar
                                    </Button>

                                    <p className="text-center text-sm text-muted-foreground">
                                        Sudah punya akun?{' '}
                                        <Link href="/login" className="text-primary underline-offset-4 hover:underline">
                                            Login
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
