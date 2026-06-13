import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';

type Props = {
    isCompleted: boolean | number | string | null | undefined;
};

const statusConfig = {
    true: {
        label: 'Completed',
        className: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800',
    },
    false: {
        label: 'Pending',
        className: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800',
    },
} as const;

export default function TodoStatusBadge({ isCompleted }: Props) {
    const completed = isCompleted === true || isCompleted === 1 || isCompleted === '1';
    const config = statusConfig[String(completed) as keyof typeof statusConfig];

    return (
        <Badge variant="outline" className={cn(config.className)}>
            {config.label}
        </Badge>
    );
}
