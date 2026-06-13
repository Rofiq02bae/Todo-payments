import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import TodoController from '@/actions/App/Http/Controllers/TodoController';
import TodoStatusBadge from '@/components/TodoStatusBadge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import type { Todo } from '@/types';

type Props = {
    todo: Todo;
};

export default function TodoCard({ todo }: Props) {
    const handleDelete = () => {
        if (window.confirm('Are you sure you want to delete this todo?')) {
            router.delete(TodoController.destroy.url(todo));
        }
    };

    return (
        <Card>
            <CardHeader>
                <div className="flex items-start justify-between gap-4">
                    <CardTitle className="text-base">{todo.title}</CardTitle>
                    <TodoStatusBadge isCompleted={todo.is_completed} />
                </div>
            </CardHeader>
            {todo.description && (
                <CardContent className="pt-0">
                    <p className="text-sm text-muted-foreground line-clamp-2">
                        {todo.description}
                    </p>
                </CardContent>
            )}
            <CardFooter className="gap-2">
                <Button variant="outline" size="sm" asChild>
                    <Link href={TodoController.edit.url(todo)}>
                        <Pencil className="size-3.5" />
                        Edit
                    </Link>
                    <Link href={TodoController.edit.url(todo)}>
                        <Pencil className="size-3.5" />
                        Edit
                    </Link>
                </Button>
                <Button variant="destructive" size="sm" onClick={handleDelete}>
                    <Trash2 className="size-3.5" />
                    Delete
                </Button>
            </CardFooter>
        </Card>
    );
}
