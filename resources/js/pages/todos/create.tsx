import { Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import TodoController from '@/actions/App/Http/Controllers/TodoController';
import AppLayout from '@/components/AppLayout';
import Heading from '@/components/heading';
import TodoForm from '@/components/TodoForm';
import { Button } from '@/components/ui/button';

export default function Create() {
    return (
        <AppLayout>
            <Head title="Create Todo" />

            <div className="flex flex-1 flex-col gap-4 rounded-xl">
                <div className="flex items-center gap-4">
                    <Button variant="ghost" size="icon" asChild>
                        <Link href={TodoController.index.url()}>
                            <ArrowLeft className="size-4" />
                        </Link>
                    </Button>
                    <Heading
                        title="Create Todo"
                        description="Add a new task to your list."
                    />
                </div>

                <div className="max-w-lg">
                    <TodoForm />
                </div>
            </div>
        </AppLayout>
    );
}

// Create.layout = {
//     breadcrumbs: [
//         {
//             title: 'Todos',
//             href: TodoController.index.url(),
//         },
//         {
//             title: 'Create',
//             href: TodoController.create.url(),
//         },
//     ],
// };
