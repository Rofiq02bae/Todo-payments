import { Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import TodoController from '@/actions/App/Http/Controllers/TodoController';
import AppLayout from '@/components/AppLayout';
import Heading from '@/components/heading';
import TodoForm from '@/components/TodoForm';
import { Button } from '@/components/ui/button';
import type { Todo } from '@/types';

type Props = {
    todo: Todo;
};

export default function Edit({ todo }: Props) {
    return (
        <AppLayout>
            <Head title={`Edit: ${todo.title}`} />

            <div className="flex flex-1 flex-col gap-4 rounded-xl">
                <div className="flex items-center gap-4">
                    <Button variant="ghost" size="icon" asChild>
                        <Link href={TodoController.index.url()}>
                            <ArrowLeft className="size-4" />
                        </Link>
                    </Button>
                    <Heading
                        title="Edit Todo"
                        description="Update the details of your task."
                    />
                </div>

                <div className="max-w-lg">
                    <TodoForm todo={todo} />
                </div>
            </div>
        </AppLayout>
    );
}

// Edit.layout = {
//     breadcrumbs: [
//         {
//             title: 'Todos',
//             href: TodoController.index.url(),
//         },
//         {
//             title: 'Edit',
//             href: '#',
//         },
//     ],
// };
