import { Form } from '@inertiajs/react';
import TodoController from '@/actions/App/Http/Controllers/TodoController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Todo } from '@/types';

type Props = {
    todo?: Todo;
};

export default function TodoForm({ todo }: Props) {
    const isEditing = !!todo;

    const formAction = isEditing
        ? TodoController.update.form(todo.id)
        : TodoController.store.form();

    return (
        <Form
            {...formAction}
            className="space-y-6"
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="title">Title</Label>
                        <Input
                            id="title"
                            name="title"
                            defaultValue={todo?.title}
                            required
                            placeholder="What needs to be done?"
                        />
                        <InputError message={errors.title} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="description">Description</Label>
                        <textarea
                            id="description"
                            name="description"
                            defaultValue={todo?.description ?? ''}
                            data-slot="textarea"
                            className="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive flex field-sizing-content min-h-16 w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            placeholder="Optional description..."
                            rows={4}
                        />
                        <InputError message={errors.description} />
                    </div>

                    {isEditing && (
                        <div className="flex items-center gap-2">
                            <Checkbox
                                id="is_completed"
                                name="is_completed"
                                defaultChecked={todo?.is_completed}
                                value="1"
                            />
                            <Label htmlFor="is_completed">Mark as completed</Label>
                        </div>
                    )}

                    <div className="flex items-center gap-4">
                        <Button disabled={processing}>
                            {isEditing ? 'Update Todo' : 'Create Todo'}
                        </Button>
                    </div>
                </>
            )}
        </Form>
    );
}
